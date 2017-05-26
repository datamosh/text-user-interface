<!--
Component:
'tui-sidebar': Sidebar

Example:
<tui-sidebar
	title="/">
	<tui-sidebar-file v-for="file in files"
		:name="file.name"
		:type="file.type"
		:content="file.content"
	></tui-sidebar-file>
</tui-sidebar>
-->

<template>
	<div class="frame sidebar" data-focus="true">
		<div class="title">{{ title }}</div>
		<ul class="files">
			<slot></slot>
		</ul>
	</div>
</template>

<script>
	export default {
		props: {
			title: {
				type: String,
				default: null
			}
		},

		data () {
			return {
				path_cursor: null
			}
		},

		methods: {
			shortcut: function(event) {
				event.preventDefault()

				if (keyboard._paused)
					return

				const vm = this,
					keys = Object.keys(vm.$parent.files),
					index_current = keys.indexOf(vm.$parent.selected)

				// Up & Down (watch?v=hlv672jqbtE)
				if (['ArrowUp', 'ArrowDown', 'PageUp', 'PageDown'].indexOf(event.key) >= 0) {
					if (event.key == 'ArrowUp')
						vm.$parent.selected = keys[index_current - 1] || keys[0]

					if (event.key == 'ArrowDown')
						vm.$parent.selected = keys[index_current + 1] || keys[keys.length - 1]

					if (event.key == 'PageUp')
						vm.$parent.selected = keys[index_current - 10] || keys[0]

					if (event.key == 'PageDown')
						vm.$parent.selected = keys[index_current + 10] || keys[keys.length - 1]

					// Open selected file
					let selected = vm.$parent.files[vm.$parent.selected]
					if (selected.name && selected.type == 'file')
						vm.$router.push(selected.path)

					// Update scroll
					vm.scroll()

					// Emit from parent change_path event. Useful for other compoents
					vm.$parent.$emit('change_path', vm.$parent.files[vm.$parent.selected], vm.$parent.selected)
				}

				// Enter
				if (event.key == 'Enter') {
					let path = vm.$parent.files[vm.$parent.selected]

					// File
					if ((path.name && path.type == 'file') || (path.name && path.type == 'link'))
						vm.$router.push(path.path)
					else
						// Folder
						vm.$router.push((vm.$parent.folder + '/' + vm.$parent.selected).replace('//', '/'))
				}

				// Limit keyboard responsiveness
				keyboard.pause()
				setTimeout(function() {
					keyboard.resume()
				}, 50)
			},

			focus: function() {
				const vm = this

				keyboard.setContext('sidebar')
				vm.scroll()

				// Change Statusbar text
				vm.$parent.status_text = config.messages.sidebar_status
			},

			scroll: function() {
				const vm = this

				// Fix scroll on sidebar
				let li = document.querySelectorAll('[data-name="' + vm.$parent.selected + '"]')[0],
					list = li.parentNode
				list.scrollTop = (li.offsetTop + 16) - list.offsetHeight
			}
		},

		created: function() {
			const vm = this

			// Keyboard shortcuts
			keyboard.withContext('sidebar', function() {
				keyboard.bind('up', vm.shortcut)
				keyboard.bind('down', vm.shortcut)
				keyboard.bind('pageup', vm.shortcut)
				keyboard.bind('pagedown', vm.shortcut)
				keyboard.bind('enter', vm.shortcut)
			})

			// Append to focusable array
			vm.$parent.focusable.push(vm)
		},

		mounted: function() {
			const vm = this

			// Set focus on sidebar
			this.focus()

			// Emit from parent change_path event. Useful for other compoents
			vm.$parent.$emit('change_path', vm.$parent.files[vm.$parent.selected], vm.$parent.selected)
		}
	};
</script>

<style lang="scss">
	@import './src/assets/sass/config';

	// Sidebar
	.sidebar {
		display: flex;
		flex: 0 0 190px;
		height: 100%;
		outline: 1px solid $color-default;
		outline-offset: -8px;
		margin-right: -15px;

		ul {
			color: $color-default;
			background: $color-background;
			border: 0;
			overflow-x: hidden;
			overflow-y: auto;
			outline: none;

			&::-webkit-scrollbar {
				width: 8px;
			}

			&::-webkit-scrollbar-track {
				background: darken($color-background, 20%);
			}

			&::-webkit-scrollbar-thumb {
				background: $color-default;
			}
		}
	}
</style>