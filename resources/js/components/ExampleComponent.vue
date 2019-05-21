<template>
    <div>
		<button :type="type" v-text="text">My Button</button>
	</div>
</template>

<script>
	export default {
		props:[
			'type',
			'text'
		]
	}
</script>
