<template>
	<div class="ani-slideInDown row justify-content-center">
		<div class="col-lg-6">
			<ToDoInput @eventAddNewTask="onAddNewTask"
             :remain="remain" 
             :spent="spent" 
            />

			<ul class="list mt-3">
				<ListItem 
                v-for="item in currentList" 
                :path="$route.path" 
                :key="item.id" 
                :name="item.name" 
                :description="item.description" 
                :price="item.price" 
                :oldPrice="item.price" 
                :remain="remain" 
                :id="item.id" 
                :isDone="item.isDone" 
                @eventTaskStatusChange="onTaskStatusChange" 
                @eventTaskEdit="onTaskEdit" 
                @eventTaskDelete="onTaskDelete" />
			</ul>
		</div>
	</div>
</template>

<script>
	// @ is an alias to /src
	import ToDoInput from "@/components/ToDoInput.vue"
	import ListItem from "@/components/ListItem.vue"

	export default {
		name: "home",
		components: {
			ToDoInput,
			ListItem
		},
		data() {
            return {
                itemList: [],
                currentList: [],
                key: this.$route.path,
                access_token: '',
                user: [],
                spent : 0,
                remain: 0,
                oldPrice: 0
            }
        },
        methods: {
            created(){
                //console.log(this.key)
               
            },
			/**
			 * Event: add new task
			 */
			onAddNewTask(name,description,price) {
                if(localStorage.getItem("User") !== null){
                this.access_token = localStorage.getItem("Token") || ''
                const user = JSON.parse(localStorage.getItem("User"))
                console.log(user.id)
				const task = {
					name: name,
                    description: description,
                    price: parseFloat(price),
					isDone: false,
                    user_id: user.id,
                    image: 'empty'
				}
                console.log(JSON.stringify(task))
                const requestOptions = {
						method: 'POST',
						headers: { 'Content-Type': 'application/json', "Authorization": "Bearer "+ this.access_token},
                        body: JSON.stringify(task)
					};
                fetch(this.$apiUrl+'/whishes', requestOptions)
					.then(async response => {
						const jsonResponse = await response.json();
						console.log(JSON.stringify(jsonResponse))
						if(jsonResponse.status != 'error'){
                            this.$alert("Éxito! Deseo añadido");
                            this.itemList.push(jsonResponse.data)
                            this.currentList.push(jsonResponse.data)
                        }else{
                            this.$alert("Error!" + JSON.stringify(jsonResponse.data));
                        }
					})
					.catch(error => {
						this.errorMessage = error;
						console.error('There was an user error!', error);
					});	
                }else{
                    this.$alert("Error: Por favor añada sus datos de usuario para continuar");
                }			
			},

            /**
             * Event: on task status changed
             */
            onTaskStatusChange(id, checked) {
                console.log(id, checked)

                let item = this.itemList.find(i => i.id == id)
               
                if (item) {
                  item.isDone = checked
                  this.access_token = localStorage.getItem("Token") || ''
                // Request 
                console.log(JSON.stringify(item))
                    const requestOptions = {
                            method: 'PUT',
                            headers: { 'Content-Type': 'application/json', "Authorization": "Bearer "+ this.access_token},
                            body: JSON.stringify(item)
                        };
                        fetch(this.$apiUrl+'/whishes/'+item.id, requestOptions)
                        .then(async response => {
                            const jsonResponse = await response.json();
                            console.log(JSON.stringify(jsonResponse))
                            item.isDone = checked
                        })
                        .catch(error => {
                            this.errorMessage = error;
                            console.error('There was an user error!', error);
                        });	        
                 
                }
                
                
            },
            /**
             * Event: on edit wish
             */
            onTaskEdit(id, name, description, price) {
                console.log("onTaskEdit")
                console.log(id, name, description, price)
                let item = this.itemList.find(i => i.id == id)
                const newItem = {name,description,price}
               
               
                if (item) {
                    this.access_token = localStorage.getItem("Token") || ''
                    // Request 
                    console.log("ITEM"+JSON.stringify(item))
                        const requestOptions = {
                            method: 'PUT',
                            headers: { 'Content-Type': 'application/json', "Authorization": "Bearer "+ this.access_token},
                            body: JSON.stringify(newItem)
                        };
                        fetch(this.$apiUrl+'/whishes/'+item.id, requestOptions)
                        .then(async response => {
                            const jsonResponse = await response.json();
                            console.log(JSON.stringify(jsonResponse))
                            item.name = name
                            item.description = description
                            item.price = parseFloat(price)
                        })
                        .catch(error => {
                            this.errorMessage = error;
                            console.error('There was an user error!', error);
                        });	 
                    }               
            },

            /**
             * Event: on task deleted
             */
            onTaskDelete(id) {
                console.log(id)

                let index = this.itemList.findIndex(i => i.id == id)
                if (index > -1) {
                    this.itemList.splice(index, 1)
                    this.access_token = localStorage.getItem("Token") || ''
                    // Request 
                        const requestOptions = {
                            method: 'DELETE',
                            headers: { 'Content-Type': 'application/json', "Authorization": "Bearer "+ this.access_token},
                        };
                        fetch(this.$apiUrl+'/whishes/'+id, requestOptions)
                        .then(async response => {
                            const jsonResponse = await response.json();
                            console.log(JSON.stringify(jsonResponse))
                        })
                        .catch(error => {
                            this.errorMessage = error;
                            console.error('There was an user error!', error);
                        });	
                   
                }

                console.log(this.itemList)
            },

            /**
             * Load item list from local storage
             */
            loadItemList(path) {
                console.log("path "+path)
                this.itemList = JSON.parse(localStorage.getItem("Wishes"))
                this.access_token = localStorage.getItem("Token") || ''
                // Request to get whishes if there is not local storage
                if(localStorage.getItem("Wishes") === null){
                     if(localStorage.getItem("User") !== null){
                          const requestOptions = {
                            method: 'GET',
                            headers: { 'Content-Type': 'application/json', "Authorization": "Bearer "+ this.access_token}
                        };
                        fetch(this.$apiUrl+'/whishes', requestOptions)
                        .then(async response => {
                            const jsonResponse = await response.json();
                            console.log(JSON.stringify(jsonResponse))
                            if(jsonResponse.status != 'error'){
                                localStorage.setItem("Wishes", JSON.stringify(jsonResponse.data))
                                this.itemList = jsonResponse.data
                                // this.currentList.push(task)
                            }else{
                                this.$alert("Error!" + JSON.stringify(jsonResponse.data));
                            }
                        })
                        .catch(error => {
                            this.errorMessage = error;
                            console.error('There was an user error!', error);
                        });	
                     }
                }

                //Compare if path is whishes list or whishes are done
                if(path === "/"){
                    this.currentList = this.itemList.filter(obj => {
                        return !obj.isDone 
                    })
                }else{
                    this.currentList = this.itemList.filter(obj => {
                        return obj.isDone 
                    })
                }
                console.log(JSON.stringify(this.itemList))
            },

            /**
             * Update the item list to local storage
             */
            updateItemList() {
                if(this.itemList != [] && this.itemList != null){
                    localStorage.setItem("Wishes", JSON.stringify(this.itemList))
                }
               
                if(this.$route.path === "/"){
                    this.currentList = this.itemList.filter(obj => {
                        return !obj.isDone 
                    })
                }else{
                    this.currentList = this.itemList.filter(obj => {
                        return obj.isDone 
                    })
                }
               
            }
        },
        mounted() {
            // Load item list from local storage
            this.loadItemList(this.$route.path)
           
        },
        watch: {
            itemList: {
                handler(value) {
                    const sum = this.itemList
                            .map(item => item.price)
                            .reduce((prev, curr) => prev + curr, 0);
                    console.log("item changed"+sum)
                    const user = JSON.parse(localStorage.getItem("User"))
                    this.spent = sum
                    this.remain = user.budget - sum
                    console.log("remaining"+this.remain)
                    // save to localStorage
                    this.updateItemList()
                },
                deep: true
            },
            '$route' () {
                //console.log(this.$route.path)
                this.loadItemList(this.$route.path)
            }
        }
	}
</script>
