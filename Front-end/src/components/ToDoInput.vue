<template>
	<div class="container">
    <div>
		<div class="row">
			<div class="col title">
				
				<b-button @click="showModal = !showModal">Agregar deseo</b-button>
			</div>
			<div class="col title">
				<p>Gastado${{this.spent}} </p>
				<p>Restante ${{this.remain}} </p>
		  	</div>
			
		</div>
      <b-modal v-model="showModal" title="Nuevo Deseo" @ok="onAddNewTask">
		<p v-if="errors.length">
			<b>Por favor, corrija el(los) siguiente(s) error(es):</b>
			<ul>
			<li v-for="error in errors" :key="error">{{ error }}</li>
			</ul>
		</p>
		<input type="text" placeholder="Nombre" v-model="name">
		<input type="text" placeholder="Descripción" v-model="description">
		<input type="number" placeholder="Precio" v-model="price">
      </b-modal>
    </div>    
  </div>	     
</template>

<script>
	export default {
		name: "ToDoInput",
		props: {
            remain: {
                type: Number,
                default: 0
            },
			spent: {
                type: Number,
                default: 0
            }
        },
		data() {
			return {
				name: "",
				description: "",
				price: "",
				showModal : false,
				errors: []
			}
		},
		methods: {
			checkForm: function (e) {
			if (this.name && this.description) {
				return true;
			}

			this.errors = [];

			if (!this.name) {
				this.errors.push('El nombre es obligatorio.');
			}
			if (!this.description) {
				this.errors.push('La descripción es obligatoria.');
			}
			if (!this.price) {
				this.errors.push('El precio es obligatorio.');
			}
			
			return false
			},
			/**
			 * Event: add new task
			 */
			onAddNewTask(e) {
				
				let checked = this.checkForm(e)
				if(checked){
					const name = this.name
					const description = this.description
					const price = this.price
 					if(this.price <= this.remain){
						this.$emit("eventAddNewTask", name,description,price)
						// reset the textbox
						this.name = ""
						this.description = ""
						this.price = ""
						this.showModal = false
						this.errors = [];
					}else{
						this.$alert("Error! No cuentas con suficiente presupuesto!");                    
					}
					
				}else{
					e.preventDefault()
				}
				
			}
		}
	}
</script>

