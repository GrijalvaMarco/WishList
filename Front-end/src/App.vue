<template>
	<div id="app" class="container py-5">
		<h1 class="fs-h1 text-center title">
			Lista de deseos App
		</h1>

		<div class="nav text-center mt-3 justify-content-center">
			<router-link to="/"><font-awesome-icon icon="tasks"/></router-link>
			<router-link to="/whishes"><font-awesome-icon icon="shopping-cart"/></router-link>
			<router-link to="/about"><font-awesome-icon icon="user"/></router-link>
		</div>

		<div class="main-content mt-5">
			<router-view />
		</div>
		
	</div>
</template>

