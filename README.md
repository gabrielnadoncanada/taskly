# MiniWMS

# Development

## Installation

1.  Install docker on your machine.  
    https://learn.microsoft.com/en-us/windows/wsl/setup/environment
1.  Copy the file ".env.example" to ".env" and change appropriate values (DB_HOST=mariadb)

1.  Changer de branche pour aller sur la branche "dev".

1.  Start VS Code and run Dev Containers by selecting from the Command Palette(F1) the following command : `Open Folder in Container...`.

1.  Installer les packages js  
    `npm install`
1.  Rouler en dev  
    `npm run dev`
1.  Avant de push sur dev, vous pouvez tester le pipeline en faisant  
    `composer test`

## Debug

1. VScode debug configuration:  
    `{
     "name": "Listen for XDebug",
     "type": "php",
     "request": "launch",
     "port": 9003,
     "pathMappings": {
         "/var/www/html": "${workspaceFolder}"
     },
     "hostname": "localhost",
     "xdebugSettings": {
         "max_data": 65535,
         "show_hidden": 1,
         "max_children": 100,
         "max_depth": 5
     }
 }
`

2. to debug tests, execute `export XDEBUG_SESSION=1` in console

3. If xdebug is broken for an obscure reason:
    1. execute `grep nameserver /etc/resolv.conf` in UBUNTU (not the container)
    2. add `SAIL_XDEBUG_CONFIG="-client_host=[the ip from previous step]"`
