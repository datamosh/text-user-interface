<!--
Component:
'tui-sidebar-file': Sidebar_file

Example:
<tui-sidebar
	title="/">
	<tui-sidebar-file v-for="file in files"
		:name="file.name"
		:type="file.type"
	></tui-sidebar-file>
</tui-sidebar>
-->

<template>
	<li class="file" :data-type="type" :data-name="name" :data-selected="is_selected(name)">
		<router-link :to="{ path: get_path(name, path) }">
			<template v-if="!path">/</template>{{ name }}
		</router-link>
	</li>
</template>

<script>
	export default {
		props: {
			name: {
				type: String,
				default: null
			},
			type: {
				type: String,
				default: null
			},
			path: {
				type: String,
				default: null
			}
		},

		methods: {
			get_path: function(name, path) {
				const vm = this
				return encodeURIComponent(path || app.folder + '/' + name, '/').replace(/%2F/g, '/').replace('//', '/')
			},

			is_selected: function(name) {
				var vm = this
				return app.selected == name
			}
		}
	};
</script>

<style lang="scss">
	@import './src/assets/sass/config';

	// Items
	.sidebar .files {
		flex: 1;
		margin: 12px;
		margin-top: 24px;

		// Item
		.file {
			display: block;
			width: 100%;
			height: 16px;
			overflow: hidden;
			outline: none;

			// General style
			a {
				text-transform: uppercase;
				outline: none;
			}

			// File style
			&[data-type="file"] {
				a {
					text-transform: lowercase;
				}
			}

			// Item: Selected
			&[data-selected="true"] {
				background-color: $color-default;
				color: $color-background;
			}
		}
	}
</style>