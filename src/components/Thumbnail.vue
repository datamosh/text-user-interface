<!--
Example:

<thumbnail
	image="http://placeskull.com/1680/1050"
	thumbnail="http://placeskull.com/320/240"
	link="http://www.google.com"
>
</thumbnail>
-->

<template>
	<a class="thumbnail" :style="{ backgroundImage: 'url(' + image + ')' }" :href="link"></a>
</template>

<script>
	export default {
		computed: {},

		props: {
			image: {
				type: String,
				default: null
			},
			link: {
				type: String,
				default: null
			}
		}
	};
</script>

<style lang="scss">
	@import '../assets/sass/mixins';

	.thumbnail {
		display: inline-block;
		float: left;
		background-color: lightgray;
		width: 33.33%;
		height: 240px;
		background-size: cover;
		background-position: top center;
		border: 0.05em solid #272822;

		@include max-width(640px) {
			width: 100%;
		}

		@include min-width(1400px) {
			width: 25%;
		}

		span {
			display: block;
		}
	}
</style>