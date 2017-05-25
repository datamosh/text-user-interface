<template>
	<div id="app">
		<div class="box box-main">
			<!-- Sidebar -->
			<tui-sidebar
				title="/">
				<tui-sidebar-file v-for="(file, key, index) in files"
					:name="key"
					:type="file.type"
					:path="file.path">
				</tui-sidebar-file>
			</tui-sidebar>

			<!-- Clock -->
			<tui-clock></tui-clock>

			<!-- Content -->
			<tui-content file="file.path"></tui-content>

			<!-- Statusbar -->
			<tui-statusbar></tui-statusbar>
		</div>
	</div>
</template>

<script>
	import Clock from './components/Clock.vue'
	import Sidebar from './components/Sidebar.vue'
	import Sidebar_file from './components/Sidebar_file.vue'
	import Content from './components/Content.vue'
	import Statusbar from './components/Statusbar.vue'

	export default {
		name: 'app',

		components: {
			'tui-sidebar': Sidebar,
			'tui-sidebar-file': Sidebar_file,
			'tui-clock': Clock,
			'tui-content': Content,
			'tui-statusbar': Statusbar
		},

		data () {
			return app
		},

		watch: {
			'$route' (to, from) {
				const vm = this

				let new_selected = _.last(_.trim(from.fullPath, '/').split('/'))
				vm.change_path(to.fullPath, new_selected)
			}
		},

		methods: {
			change_path: function(new_path, new_selected) {
				const vm = this

				// Set new path
				vm.path = new_path

				// Get info about the path
				let path = pathinfo(vm.path),
					files = []

				// Get folder
				vm.folder = decodeURIComponent((path.extension) ? path.dirname : vm.path)

				// Enable back from directory
				let previous_folder = vm.folder.split('/').slice(0, -1).join('/')
				if (vm.folder && vm.folder != '/') {
					files['..'] = {
						name: '..',
						path: previous_folder || '/',
						type: 'link'
					}
				}

				// Get files
				vm.files = Object.assign({}, files, disk.get(_.filter(_.trim(vm.folder, '/').split('/'))))

				// Select new_selected or first in case of error
				vm.selected = (Object.keys(vm.files).indexOf(new_selected) >= 0) ? new_selected : Object.keys(vm.files)[0]

				// Select by current path
				if (path.basename && path.extension)
					vm.selected = path.basename
			},

			shortcut: function(event) {
				const vm = this

				// Toggle focus
				if (event.key == 'Tab') {
					let focus_current = null,
						focus_next = null
					_.each(vm.focusable, function(component, index) {
						if (component.$el.getAttribute('data-focus') == 'true')
							focus_current = index
						component.$el.setAttribute('data-focus', false)
					})
					focus_next = (vm.focusable[focus_current + 1]) ? vm.focusable[focus_current + 1] : vm.focusable[0]
					focus_next.$el.setAttribute('data-focus', true)
					if (typeof focus_next.focus == 'function')
						focus_next.focus()

					event.preventDefault()
				}
			}
		},

		created: function () {
			const vm = this

			// Keyboard shortcuts
			keyboard.bind('tab', vm.shortcut)

			// Update path onload (maybe exist a better way to do this)
			vm.change_path(vm.$router.resolve(window.location.hash).location.path)
		}
	}
</script>

<style lang="scss">
	@import './src/assets/sass/mixins';
	@import './src/assets/sass/reset';
	@import './src/assets/sass/fonts';
	@import './src/assets/sass/config';

	#app {
		@include font;
		color: $color-default;
		background: $color-background;
		margin: 0 auto;
		width: 100vw;
		height: 100vh;
		padding: 4px;
		-webkit-touch-callout: none;
		-webkit-user-select: none;
		-khtml-user-select: none;
		-moz-user-select: none;
		-ms-user-select: none;
		user-select: none;

		a {
			color: inherit;
			text-decoration: none;
		}
	}

	.box-main {
		display: flex;
		width: 100%;
		height: 100%;
		outline: 1px solid $color-default;
		outline-offset: -8px;
	}

	// Frame (Common container)
	.frame {
		.title {
			position: absolute;
			height: 16px;
			left: 20px;
			top: 0;
			padding: 0 10px;
			overflow: hidden;
			background: $color-background;
			color: $color-default;
		}

		&[data-focus="true"] {
			.title {
				background: $color-default;
				color: $color-background;
			}
		}
	}
</style>
