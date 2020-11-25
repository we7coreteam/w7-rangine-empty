@ECHO OFF
setlocal DISABLEDELAYEDEXPANSION
SET BIN_TARGET=%~dp0/../vendor/bin/rangine-gerent
"%BIN_TARGET%" %*