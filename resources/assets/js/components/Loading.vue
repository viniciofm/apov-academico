<template>
	<div v-if="loading">
		<div class="center">
			<div class="loader">
				<div class="pacman">
					<div></div>
					<div></div>
					<div></div>
					<div></div>
					<div></div>
					<div></div>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
    require('loaders.css/loaders.min.css');

    export default {
        props: {
            loading: true
        }
    }
</script>

<style lang="scss" scoped>
	$color: #FEC54F;
	$bullet: #FFFFAA;

	.center {
		margin: auto;
		width: 30%;
		padding: 5rem 7rem;
		text-align: center;
		}

	.pacman {
		> div:first-of-type,
		> div:nth-child(2) {
			border-top: 25px solid $color;
			border-left: 25px solid $color;
			border-bottom: 25px solid $color;
			z-index: 999;
			}
	
		> div:nth-child(3),
		> div:nth-child(4),
		> div:nth-child(5),
		> div:nth-child(6) {
			background-color: $color;
			border: 1px solid grey;
			z-index: 998;
			}
		}
</style>