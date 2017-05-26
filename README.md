# Text User Interface

Simple file explorer with a classic interface (inspired by MS-DOS) built with Vue.js

![Text User Interface](http://i.imgur.com/TRqYKht.png)

## Keyboard shortcuts

The application is designed to be operated by the keyboard (you can also navigate with the mouse, of course).

**Global shortcuts**
| Keyboard Shortcut | Description |
| --- | --- |
| Tab | Change the focus between the sidebar and the content |

**Sidebar**
| Keyboard Shortcut | Description |
| --- | --- |
| Up & Down | Scroll through files / directories |
| PageUp & PageDown | Scroll through files / directories (faster) |
| Enter | Change directory |

**Content**
| Keyboard Shortcut | Description |
| --- | --- |
| Up & Down | Scroll the current file |

## Build Setup

``` bash
# install dependencies
yarn

# serve with hot reload at localhost:8080
yarn run dev

# build for production with minification
yarn run build
```

## Config

Remember edit config.json with your preferences

`path` Path to files. Example: files
`server` Server URL (read below for more information). Example: server_php
`init_path` Initial path, example: '/'
`sidebar_status` Statusbar text when the focus is on the sidebar
`content_status` Statusbar text when the focus in on the content

## Server

The file listing can use a JSON file or use the PHP server that includes the project (/server_php)