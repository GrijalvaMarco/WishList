<template>
	<li class="list-item" :class="{ done: isDone }">
        <b-modal v-model="showModal" title="¿Estás seguro que deseas eliminar este deseo?" @ok="onTaskDelete">	
        </b-modal>
        <b-modal v-model="showEditModal" title="Editar Deseo" @ok="onTaskEdit">
		<p v-if="errors.length">
			<b>Por favor, corrija el(los) siguiente(s) error(es):</b>
			<ul>
			<li v-for="error in errors" :key="error">{{ error }}</li>
			</ul>
		</p>
		<input type="text" placeholder="Nombre" v-model="name" required>
		<input type="text" placeholder="Descripción" v-model="description">
        <input type="number" placeholder="Precio" v-model="tempPrice">
      </b-modal>
        <input v-if="path === '/'" type="checkbox" class="checkbox" 
        :id="idComputed" 
        @change="onTaskStatusChange" 
        :checked="isDone">
        <label class="mr-3" :for="idComputed"></label>
        <div>
            <img class="img-fluid" :for="idComputed" :src="'http://via.placeholder.com/50x50'" />
            <label class="mr-3" :for="idComputed"></label>
            <span class="text">
                Precio: ${{price}}
            </span>
        </div>  
        <label class="mr-3" :for="idComputed"></label>
        <span class="text">
            {{ name }}
        </span>
        <label class="mr-3" :for="idComputed"></label>
        <span class="text">
            {{ description }}
        </span>
        <!-- <span class="icon-delete" @click="onTaskDelete"></span> -->
        <div>
            <span  v-if="path === '/'" class="icons" @click="showEditModal = !showEditModal"><font-awesome-icon icon="edit"/></span>
            <span class="icons" @click="showModal = !showModal"><font-awesome-icon icon="trash"/></span>
        </div>
        
    </li>
</template>

<script>
	export default {
        name: "ListItem",
        props: {
            path: {
                type: String,
                default: ""
            },
            id: {
                type: Number,
                default: 0
            },
            name: {
                type: String,
                default: ""
            },
             description: {
                type: String,
                default: ""
            },
             price: {
                type: Number,
                default: 0
            },
            oldPrice: {
                type: Number,
                default: 0
            },
             remain: {
                type: Number,
                default: 0
            },
            isDone: {
                type: Boolean,
                default: false
            }
        },
        data() {
			return {
				showModal : false,
                showEditModal: false,
                errors: [],
                showPath: false,
                tempPrice: this.price
			}
		},
        computed: {
            idComputed() {
                return `item-${this.id}`
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
             * Event: on task status changed checkbox
             */
            onTaskStatusChange(e) {
                const checked = e.target.checked
                this.$emit("eventTaskStatusChange", this.id, checked)
            },

            /**
             * Event: on task deleted
             */
            onTaskDelete(e) {
                this.$emit("eventTaskDelete", this.id)
            },
            /**
             * Event: on task edited
             */
            onTaskEdit(e) {
                console.log(this.name)
                 if(this.tempPrice <= (this.remain + this.oldPrice)){
                    this.price = this.tempPrice
                    this.$emit("eventTaskEdit", this.id, this.name, this.description, this.price)
                 }else{
                    this.$alert("Error! No cuentas con suficiente presupuesto!");                    
                }
            }
        }
    }
</script>

