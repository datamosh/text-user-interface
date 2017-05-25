<!--
Component:
'tui-statusbar': Statusbar

Example:
<tui-statusbar></tui-statusbar>
-->

<template>
	<div class="statusbar">
		{{ this.$parent.status_text }}
	</div>
</template>

<script>
	export default {
		created: function() {
			const vm = this

			vm.$parent.$on('status_text', function(text) {
				vm.text = this
			})
		}
	};
</script>

<style lang="scss">
	@import './src/assets/sass/config';

	.statusbar {
		position: absolute;
		right: 20px;
		bottom: 0;
		background: $color-background;
	}
</style>