# CHANGELOG

## 0.1.0

 * `CloverDiff::compare()` can now compare Clover XML files for the same project from different operating systems in most cases.
 * BC Break : `CloverDiff::compare()` now accepts an array of Clover XML file paths allowing arbitrary number of Clover XMLs to be compared.
 * BC Break : `CloverDiff::compare()` now returns a `DirectoryNode` instance which holds all information about the comparison.
 * BC Break : `CloverDiff::compare()` now throws exception when less than two Clover XML file paths were provided as an argument.
 * BC Break : `CloverDiff::compare()` now throws exception when the specified Clover XML files cannot be compared.

## 0.0.1

 * Initial release.
