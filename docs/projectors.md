Projectors
===========

Projectors are an important concept in Blumba. By default for an event-sourced system, the `EventRecorder` will call a list of
`Projector`s for each event that will be of interest to that `Projector`. There is a convention for naming the handler methods
for such events:

Given an event named `UserWasRegisteredEvent` the `UserProjector` would have a method called:

```php
public function projectUserWasRegistered(UserWasRegisteredEvent $event);
```

If no method is found on the `Projector` matching this convention for an event that is attempting to fire an exception of type
`ProjectorCouldNotProjectEventException` will be thrown.
