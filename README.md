Symfony Job Bundle
==========================

A symfony bundle that allows asynchronous processing of jobs.

## Introduction

What is the idea behind this bundle. Explain a bit the overall architecture (manager, queue backend, scheduler).

## Installation

Add the bundle:

``` json
{
    "require": {
        "aboutcoders/job-bundle": "dev-master"
    }
}
```

Enable the bundles in the kernel:

``` php
# app/AppKernel.php
public function registerBundles()
{
    $bundles = array(
        // ...
        new Sonata\NotificationBundle\SonataNotificationBundle(),
        new FOS\RestBundle\FOSRestBundle(),
        new JMS\SerializerBundle\JMSSerializerBundle(),
        new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
        new Nelmio\ApiDocBundle\NelmioApiDocBundle(),
        new \Abc\Bundle\ProcessControlBundle\AbcProcessControlBundle(),
        new \Abc\Bundle\SchedulerBundle\AbcSchedulerBundle(),
        new Abc\Bundle\JobBundle\AbcJobBundle(),
        // ...
    );
}
```

Follow the installation and configuration instructions of the third party bundles:

* [SonataNotificationBundle](http://sonata-project.org/bundles/notification/master/doc/index.html)
* [AbcSchedulerBundle](https://bitbucket.org/hasc/scheduler-bundle)

Configure the bundle

``` yaml
# app/config/config.yml
abc_job:
  db_driver: orm
  job_logs_dir: "%kernel.logs_dir%"
```

Add a new doctrine mapping types

``` yaml
# app/config/config.yml
doctrine:
    dbal:
        types:
            status: Abc\Bundle\JobBundle\Doctrine\Types\StatusType
            serializable: Abc\Bundle\JobBundle\Doctrine\Types\SerializableType
```


## Usage

### Execute a job

```php
// retrieve the job manager from the service container
$manager = $container->get('abc.job.manager');

$ticket = $manager->addJob('mailer', $parameters);
```

The first parameter specifies the type of the job whereas the second parameter must implement the interface \Serializable.

Whenever you add a new job you will get a ticket that you can use to retrieve information about the job.

### Get information about a job

#### Status

Retrieve current status of a job:

```php
$status = $manager->getStatus($ticket)
```

The following status values are defined:

```php
const REQUESTED  = 1;
const PROCESSING = 2;
const SLEEPING   = 3;
const PROCESSED  = 4;
const CANCELLED  = 5;
const ERROR      = 6;
```

#### Logs

Each job logs to it's own log file.  You can use the manager to get the logs of a certain job:

```php
$string = $manager->getLogs($ticket)
```

#### Reports

Retrieve a report of a job:

```php
$report = $manager->getReport($ticket)

Please take a look at the api documentation (Abc\Bundle\JobBundle\Job\Report\ReportInterface) to get a overview of the information available in a report.

### Defining a new job

In order to register a new job, you have to take these two steps:

- Create a new executable
- Register the executable in the service container

An executable must implement the interface Executable, which defines only one method execute. This method receives a Job as argument.

```php
class Mailer implements Executable
{
    protected $mailer;

    /**
     * @param \Foo\Bar\Mailer $mailer
     */
    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * {@inheritdoc}
     */
    public function execute(Job $job)
    {
        /** @var Abc\Demo\Message $mail */
        $mail = $job->getParameters();

		$message=$this->mailer->createMessage();
		$message->setTo($mail->getTo());
		
		...
		
		$this->mailer->send($message);
    }
}
```

The last step is to register the executable in the service container. This must be done by specifying a custom tag 'abc.job.listener' with a type.

```xml
<?xml version="1.0" ?>
<container xmlns="http://symfony.com/schema/dic/services"
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xsi:schemaLocation="http://symfony.com/schema/dic/services http://symfony.com/schema/dic/services/services-1.0.xsd">
    <services>
        <service id="abc.foobar.mailer" class="Abc\Foobar\Mailer">
            <tag name="abc.job.listener" type="mailer" level="debug" />
        </service>
    </services>
</container>
```

### Advanced job execution

#### Scheduled job

To be documented...

#### Child jobs

Within your current job you can request the execution of another job (child job).

```php
// request execution of another job
$ticket = $job->addChildJob('AnotherJob', $parameters);
```

Whenever a child job terminates the parent job will be executed again. Within your current job you can get information about the child job that is calling back.

```php
// check if job was called back by a child job
if($job->isTriggeredByCallback())
{
    // get the job that is calling back
    $childJob = $job->getCallerJob();
}
```

The lifecycle of a job ends when all it's child jobs and the job itself are terminated.

#### Context

During every execution of a job you have access to a context. The context acts like a container where properties (parameters, objects) can be set. The context is available during the overall lifecycle of a job and shared between the job and it's child jobs.

```php
    $job->getContext();
```

After each execution of the job the context gets persisted. All properties defined in the context that are of the types:

    - string
    - integer
    - double
    - float
    - \Serializable

will be persisted. Properties of a different type will be removed from the context.

#### Logging

You have access to a standard PSR logger during the execution of a job.

```php
$job->getContext()->get('logger')->debug('A debug message');
```

This logger collects information of a single job execution and it's child jobs.

#### Response

To be documented.

Please take a look at the interface Abc\Bundle\JobBundle\Job\Job to get an overview of the functions that are available in the execution context of a job.

## Lifecycle Events

During the lifecycle of a job execution events are dispatched you to hook into.

### Job Preparation

Before every execution of a job an event with the name 'abc.job.prepare' is dispatched to all registered listeners. Listeners here have the option to e.g set properties in the context. The logger for example is registered in the context with this approach.

In order to register an event listener for the event 'abc.job.prepare' just have to create a service and configure a specific tag for it.

```yml
# app/config/config.yml
services:
    kernel.listener.your_listener_name:
        class: Acme\DemoBundle\EventListener\MyJobListener
        tags:
            - { name: abc.job.event_listener, event: abc.job.prepare, method: onPrepare }
```

Within this listener you can now add custom properties to the context

```php

namespace Abc\MyBundle\EventListener;

use Abc\Bundle\JobBundle\Event\JobEvent;

class MyJobListener
{
    public function onPrepare(JobEvent $job)
    {
        $parameter = new Path/To/Custom/Parameter();

        $job->getContext()->set('custom', $parameter);
    }
}
```

### Job Report

Whenever a report is requested from the manager  an event with the name 'abc.job.report' is dispatched to all registered listeners. This event allows you to modify/extend the report before it is returned.

```yml
# app/config/config.yml
services:
    kernel.listener.your_listener_name:
        class: Acme\DemoBundle\EventListener\MyJobListener
        tags:
            - { name: abc.job.event_listener, event: abc.job.report, method: onReport }
```

Within the listener you can now do something with the report:

```php

namespace Abc\MyBundle\EventListener;

use Abc\Bundle\JobBundle\Event\JobEvent;

class MyJobListener
{
    public function onReport(ReportEvent $event)
    {
        $report = $event->getReport();

        $event->setReport(new MyCustomReport($report));
    }
}
```

### Job Termination

After each termination of a root job an event with the name 'abc.job.terminated' is dispatched to all registered listeners. The report provides detailed information about the execution of a job.

```yml
# app/config/config.yml
services:
    kernel.listener.your_listener_name:
        class: Acme\DemoBundle\EventListener\MyJobListener
        tags:
            - { name: abc.job.event_listener, event: abc.job.terminated, method: onTerminate }
```

Within the listener you can now do something with the report:

```php

namespace Abc\MyBundle\EventListener;

use Abc\Bundle\JobBundle\Event\JobEvent;

class MyJobListener
{
    public function onTerminate(ReportEvent $event)
    {
        $report = $event->getReport();
    }
}
```