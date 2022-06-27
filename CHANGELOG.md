# ColorMate Changelog

## 2.2.1.2 - 2022-06-27
### Fixed
- Fixed regression that would cause custom colors to always be set to null.
 
## 2.2.1.1 - 2022-06-22
### Fixed
- Fixed stupid copypaste bug
 
## 2.2.1 - 2022-06-22
### Fixed
- Fixed issue where having a site handle that matched one of colormates preset properties, could lead to errors.

## 2.2.0 - 2022-06-20
### Added
- Added support for different presets per site

### Fixed
- Fixed default value handling

## 2.1.0 - 2022-05-11
### Added
- Added "Preview Mode" field setting  

## 2.0.0 - 2022-05-04
### Added
- Added Craft 4 version
- Added tooltips to palette colors (fixed #12)
 
### Fixed
- Fixed an error that would occur if a field was configured with a preset that no longer exists (fixed #13)

## 1.0.7 - 2022-05-02
### Fixed
- Fixed incorrect calculation of alpha component in output color

## 1.0.6 - 2022-02-01
### Added
- Added color picker to custom color selection

### Fixed
- Fixed minor visual glitch when not using a palette

## 1.0.5 - 2022-01-28
### Added
- Added/implemented PreviewableFieldInterface 

## 1.0.4 - 2021-08-03
### Fixed  
- Fixed issue where ColorMate could trigger unneccessary window unload confirm dialogs in element editor huds and slideouts  

## 1.0.3 - 2020-08-20
### Fixed
- Fixed issue that could cause an exception when previewing a draft (fixes #3).

## 1.0.2 - 2020-06-11
### Added
- Added/implemented `showClear` preset config setting.

## 1.0.1.1 - 2020-05-21
### Fixed
- Fixed a regression error due to error in `normalizeValue`.

## 1.0.1 - 2020-05-21
### Fixed
- Fixed issues that could occur when changing an existing field to ColorMate.

## 1.0.0 - 2020-05-21
### Added
- Initial public release
