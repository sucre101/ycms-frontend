#!/bin/bash

cd ./app-builds/$1

#cat config.v$2.json | jq '.modules.webview.id'

#var3=(jq -r '.modules.webview.id' config.v$2.json)

result=$(cat config.v$2.json)

app_folder=$(jq -r '.settings.app_folder' <<< ${result})
app_id=$(jq -r '.id' <<< ${result})
backgroundSplash=$(jq -r '.settings.background_splash' <<< ${result})
appIcon=$(jq -r '.settings.app_icon_src' <<< ${result})

cd /var/www/ycms/ycms-client-panel/clients/

mkdir $app_folder

chmod 0777 $app_folder

cp -r /home/alex/Downloads/bolvanka ./$app_folder/app
rm -r ./$app_folder/app/platforms/*
cd $app_folder/app/

#chmod -R 0777 /var/www/ycms/ycms-client-panel/clients/$app_folder

cp /var/www/ycms/ycms-client-panel/backend/app-builds/$1/config.v$2.json ./app/config.json

cp /var/www/ycms/ycms-client-panel/backend/app-builds/$1/strings.xml ./app/App_Resources/Android/values/strings.xml

/home/alex/.nvm/versions/node/v10.21.0/bin/ns resources generate splashes /var/www/ycms/ycms-client-panel/backend/app-builds/$1/app_icon.png --background $backgroundSplash
/home/alex/.nvm/versions/node/v10.21.0/bin/ns resources generate icons /var/www/ycms/ycms-client-panel/backend/app-builds/$1/app_icon.png

/home/alex/.nvm/versions/node/v10.21.0/bin/ns prepare android

cp ./app/App_Resources/Android/values/strings.xml ./platforms/android/app/src/main/res/values/strings.xml

/home/alex/.nvm/versions/node/v10.21.0/bin/ns build android

#cp /var/www/ycms/ycms-client-panel/clients/$app_folder/app/platforms/android/app/build/outputs/apk/debug/app-debug.apk ./app/app.apk

#/home/alex/.nvm/versions/node/v10.21.0/bin/ns test ios

# curl http://api.ycms/ycms/confirm/$app_id
