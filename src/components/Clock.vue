<!--
Component:
'tui-clock': Clock

Example:
<tui-clock></tui-clock>
-->

<template>
	<div class="clock">
		{{ hours }}:{{ minutes }}
	</div>
</template>

<script>
	export default {
		data() {
			return {
				hours: 0,
				minutes: 0,
				seconds: 0
			}
		},

		created() {
			setInterval(this.update_time, 1000)
		},

		methods: {
			getZeroPad (n) {
				return ('00' + n).substr(-2, 2)
			},

			update_time () {
				let now = new Date()
				this.hours = this.getZeroPad(now.getHours())
				this.minutes = this.getZeroPad(now.getMinutes())
				this.seconds = this.getZeroPad(now.getSeconds())
			}
		}
	};
</script>

<style lang="scss">
	@import './src/assets/sass/config';

	.clock {
		position: absolute;
		right: 20px;
		top: 0;
		background: $color-background;
	}
</style>