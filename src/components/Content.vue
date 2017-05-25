<!--
Component:
'tui-content': Content

Example:
<tui-content file="file.path"></tui-content>
-->

<template>
	<div class="frame content" data-focus="false">
		<div class="title">{{ file }}</div>
		<div class="container" tabindex="0" v-html="content"></div>
	</div>
</template>

<script>
	import * as mime from 'mime'
	import * as request from 'superagent'

	export default {
		data () {
			return {
				content: '',
				file: '',
				request: null
			}
		},

		created: function() {
			const vm = this

			vm.$parent.$on('change_path', vm.get_file)

			// Append to focusable array
			vm.$parent.focusable.push(vm)
		},

		methods: {
			get_file: function(file, name) {
				const vm = this

				// Only load same file once
				if (vm.file == file.path)
					return

				vm.file = file.path
				vm.content = ''

				// Abort previous request
				if (vm.request)
					vm.request.abort()

				// File
				if (file.type == 'file') {
					// Set temporal content
					vm.content = 'Loading file...'

					// Content by extension
					let mimetype = mime.lookup(file.extension)

					// Text
					if (mimetype.search('text') >= 0 || mimetype == 'application/x-ms-shortcut') {
						vm.request = request
							.get(config.path + file.path)
							.query({ format: 'json' })
							.end(function(error, response) {
								vm.request = null

								// Error
								if (error)
									vm.content = 'Error reading file'

								// Set content
								if (mimetype == 'application/x-ms-shortcut')
									vm.content = '<iframe src="' + response.text + '" scrolling="no"></iframe>'
								else if (mimetype == 'text/html')
									vm.content = '<div>' + response.text + '</div>'
								else
									vm.content = '<pre>' + response.text + '</pre>'
							})
					}

					// Image
					if (mimetype.search('image') >= 0)
						vm.content = '<img src="' + config.path + file.path + '"/>'

				} else if (file.type == 'link')
					// Link (..)
					vm.content = ''
				else
					// Directory
					vm.content = name.toUpperCase() + ' is a directory'
			},

			focus: function() {
				const vm = this

				keyboard.setContext('content')

				// Set focus on the correct element to scroll
				vm.$el.querySelector('.container').focus()

				// Change Statusbar text
				vm.$parent.status_text = config.messages.content_status
			},
		}
	};
</script>

<style lang="scss">
	@import './src/assets/sass/config';

	.content {
		flex: 1;
		height: calc(100% - 16px);
		overflow: hidden;

		.container {
			display: block;
			width: calc(100% - 24px);
			height: calc(100% - 24px);
			margin: 24px 8px;
			margin-left: 16px;
			outline: 0;
			overflow-x: hidden;
			overflow-y: scroll;

			&::-webkit-scrollbar {
				width: 8px;
			}

			&::-webkit-scrollbar-track {
				background: darken($color-background, 20%);
			}

			&::-webkit-scrollbar-thumb {
				background: $color-default;
			}

			& > img {
				max-width: 100%;
			}

			& > iframe {
				width: 100%;
				height: 100%;
				overflow: hidden;
			}
		}
	}
</style>