<template>
	<div class="ani-slideInDown text-center">
		<h2 class="fs-h2">
			Mi Perfil
		</h2>
	<div>
		<label class="fw-normal" for="new-todo-input">
			Nombre:
		</label>
		<input
			type="text"
			id="name"
			name="name"
			v-model="userData.name"
		/>
		<label class="fw-normal" for="new-todo-input">
			Correo electrónico:
		</label>
		<input
			type="text"
			id="email"
			name="email"
			v-model="userData.email"
		/>
		<label class="fw-normal" for="new-todo-input">
			Presupuesto:
		</label>
		<input
			type="text"
			id="budget"
			name="budget"
			v-model="userData.budget"
		/>
		<label class="mt-8"></label>
		<button class="btn-primary active" v-on:click="onSubmit">
			Guardar
		</button>
	</div>
	</div>
</template>

<script>
	export default {
		name: "ToDoInput",
		data() {
			return {
				errors: [],
				userData : [],
			}
		},
		mounted(){
 			this.userData = JSON.parse(localStorage.getItem("User")) || []			
		},
		methods: {
			checkForm: function (e) {
			if (this.userData.name && this.userData.email && this.userData.budget) {
				return true;
			}

			this.errors = [];

			if (!this.userData.name) {
				this.errors.push('El nombre es obligatorio.');
			}
			if (!this.userData.email) {
				this.errors.push('El correo es obligatorio.');
			}
			if (!this.userData.budget) {
				this.errors.push('El Presupuesto es obligatorio.');
			}
			
			return false
			},
			onSubmit: function (e) {
				if(this.checkForm()){	
				const json = {
					id: this.userData.id,
					name: this.userData.name,
					email: this.userData.email,
					budget: this.userData.budget
				}
				console.log(JSON.stringify(json))
				const requestOptions = {
				method: 'POST',
				headers: { 'Content-Type': 'application/json' },
				body: JSON.stringify(json)
			};
			fetch(this.$apiUrl+'/auth/signup', requestOptions)
				.then(async response => {
				const jsonResponse = await response.json();
				console.log(JSON.stringify(jsonResponse))
				if (jsonResponse.status === "error") {
					// get error message from body or default to response status
					const errors = jsonResponse.data;
					this.$alert("Error: "+errors.toString());
					return Promise.reject(errors);
				}

				// Json reponse is success
				const requestLoginOptions = {
					method: 'POST',
					headers: { 'Content-Type': 'application/json' },
					body: JSON.stringify({email: this.userData.email})
				};
					fetch(this.$apiUrl+'/auth/login', requestLoginOptions)
					.then(async loginResponse => {
					const jsonLoginResponse = await loginResponse.json();
					console.log(JSON.stringify(jsonLoginResponse))
					if (jsonLoginResponse.status === "error") {
						// get error message from body or default to response status
						const errors_log = jsonLoginResponse.data;
						this.$alert("Error: "+errors_log.toString());
						return Promise.reject(errors_log);
					}
					const access_token = jsonLoginResponse.data.access_token
					localStorage.setItem("Token", access_token)
					// Json LOGIN RESPONSE is success
						const requestUserOptions = {
							method: 'GET',
							headers: { 'Content-Type': 'application/json', "Authorization": "Bearer "+ access_token}
						};
						fetch(this.$apiUrl+'/auth/user', requestUserOptions)
						.then(async userResponse => {
							const jsonUserResponse = await userResponse.json();
							console.log(JSON.stringify(jsonUserResponse))
							
							this.$alert("Éxito!: Usuario actualizado correctamente.");
							localStorage.setItem("User", JSON.stringify(jsonUserResponse.data))
							this.userData = jsonUserResponse.data
						})
						.catch(error => {
							this.errorMessage = error;
							console.error('There was an user error!', error);
						});

					})
					.catch(error => {
						this.errorMessage = error;
						console.error('There was an login error!', error);
					});
				
				})
				.catch(error => {
				this.errorMessage = error;
				console.error('There was an signup error!', error);
				});
			}else{
				this.$alert("Error: "+this.errors.toString());
			}
			}
		}
	}
</script>