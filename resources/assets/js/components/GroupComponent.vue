<template>
	
	<div>
		
			<div class="card">
                <div class="card-header">
                	
                	<ou-button type='primary' @click="showCreatePanel()">create</ou-button>
                	
                </div>

                <div class="card-body">
                    

                    <div class="row">
                    <div class="col-md-2 conts" v-for="group in groups" >

                    	<ou-button type='compound' :description='group.created_at' @click="showEditPanel(group)">{{group.name}}</ou-button>

                    
					</div>
				</div>


                </div>




                <ou-panel title='Create Group' size='medium' v-model='createPanel'>
    				

                	<ou-text-field v-model='name' label='Name' />

                	

                	<br>

                	<ou-button type='primary' @click="createGroup()">Create</ou-button>

  				</ou-panel>



  				 <ou-panel title='Manage Group' size='medium' v-model='editPanel'>
    				
  				 	<ou-button type='primary' @click="deleteGroup(group.id)">Delete</ou-button>

  				 	

  				 	<hr>
                	<ou-text-field v-model='group.name' label='Name' />

                	
                	

                	<br>

                	<ou-button type='primary' @click="updateGroup(group.id)">Update</ou-button>

  				</ou-panel>


            </div>

	</div>

</template>

<script type="text/javascript">
	
	export default {

		mounted(){

			this.getGroups()
		},

		data(){

			return {

				groups: [],
				name: '',
				group: {},
				createPanel: false,
				editPanel: false
			}
		},

		methods: {

			getGroups(){

				axios.get('/api/groups').then(response => {

					this.groups = response.data
				}).catch(error => {

					alert(error)
				})
			},

			createGroup(){

				let data = {

					name: this.name,
					
				}

				axios.post('/api/groups', data).then(response => {
					this.getGroups()
					this.createPanel = false
				}).catch(error => {
					alert(error)
				})
			},

			showCreatePanel(){

				this.createPanel = true
			},

			showEditPanel(group){
				this.group = group
				this.editPanel = true
			},


			updateGroup(id){

				let data = {

					name: this.group.name,
					
				}

				axios.put('/api/groups/'+id, data).then(response => {
					this.getGroups()
					this.editPanel = false
				}).catch(error => {
					alert(error)
				})
			},


			deleteGroup(id){

				
				axios.delete('/api/groups/'+id).then(response => {
					this.getGroups()
					this.editPanel = false
				}).catch(error => {
					alert(error)
				})
			},


		}
	}
</script>

<style>

.conts {

	font-size: 12px;
}

.table th {

	padding: 5px;
}

</style>