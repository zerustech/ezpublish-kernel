# eZ Publish legacy extension support in bundles

> Audience: integrators, extension developers
> Author: Bertrand Dunogier <bertrand.dunogier@ez.no>
> Created: 22/01/2014
> JIRA story: https://jira.ez.no/browse/EZP-22210
> Topics: BC, Extensibility

## Use-cases
### Custom extension
The developer of a custom extension, like a fieldtype, wants to make his extension available via composer, and add preliminary new stack support to it. He creates a new bundle for his extension, and copies all of its contents to `Resources/ezpublish-legacy`.
Anyone can install his legacy extension by requiring it from composer.json. The custom install script will link it into his legacy extensions folder, and enable it when the bundle is enabled.

### Website project configuration
A project's maintainer wants to gather as much as possible of his project elements into one place.
Any setting that isn't mapped by the semantical configuration can be overridden using the `Resources/ezpublish-legacy` folder, using the standard `settings/override` and `settings/siteaccess folder`. Custom legacy templates can also be created here, for instance to override a couple backoffice elements.

### Dual-kernel extension
A developer needs a custom fieldtype.
Since there is no backoffice yet, a couple legacy elements are still required (datatype class, edit/view templates, settings.
Using a Bundle, the developer can have both the new stack and legacy code in the same structure, and make sure both evolve at the same rythm:
- `Acme/Bundle/AcmeBundle/Resources/ezpublish-legacy` contains the legacy datatype elements
- `Acme/Bundle/AcmeBundle/eZ/FieldType` contains the new stack implementation

## Summary
Make it possible to place legacy code in a Symfony 2 bundle. Could be done by exposing Resources/ezpublish-legacy as a legacy extension folder.

### Benefits
- development can be made without actually going inside the ezpublish_legacy folder
- versioning and deployment is easier, since this folder can be created in the project's bundle
- makes up for the non-injection/mapping of a huge part of the legacy configuration by making it visible from the new stack structure
- legacy extensions can very easily be bundled as eZ Publish 5 bundles without changing a single line of code
- the legacy (backoffice) counterpart of new stack extensions can be bundled together with the new stack code, and automatically installed using composer

## Technical approach
- a composer script symlinks (works on windows with PHP > 5.3 as well) the folder to `ezpublish_legacy/extensions`, using the lowercased name of the bundle as the symlink name:
  `extension/ezsystemsdemobundle -> ../../vendor/ezsystems/demobundle/EzSystems/DemoBundle/Resources/ezpublish-legacy`
- the extension is injected into `site.ini/ExtensionSettings/ActiveExtensions`:

## Possible extra features

### Semi-automatic null FieldType mapping

> Benefit: makes it easier to soft migrate legacy datatype extensions to the new stack

A common use-case in projects would be to map an existing, custom legacy datatype to the [Null FieldType][1].

Any datatype extension can easily be copied to a bundle. In order not to error out on the new stack, the most basic operation is to map the datatype to the Null FieldType, by means of services definitions.

Write a compiler pass that looks for datatypes in the Resources/ezpublish-legacy folder. For each datatype found, if no matching fieldtype is registered, we declare a new service that maps the datatype to the Null FieldType (*NEED DETAILLED USE-CASE*).

### Autoload generation on install

> Benefit: removes a legacy specific manual step that is easily forgotten when installing a custom extension

Provide a composer script, that can be used in legacy bundles, that updates the extension
and kernel override (if there are such overrides) on install/update.

> Possible issues: it could, if there are several updates, run the autoload scripts more than once.

## Open questions
- What settings can *not* be overridden this way ?

  [1]: https://confluence.ez.no/display/EZP/The+Null+FieldType