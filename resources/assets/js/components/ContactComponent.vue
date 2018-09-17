<template>
	
	<div>
		
			<div class="card">
                <div class="card-header">
                	
                	<ou-button type='primary' @click="showCreatePanel()">create</ou-button>
                	<ou-button type='primary' @click="showImportPanel()">import</ou-button>
                </div>

                <div class="card-body">
                    

                    <div class="row">
                    <div class="col-md-2 conts" v-for="contact in contacts" style="padding:10px;">

                    	<ou-button type='compound' :description='contact.phone' @click="showEditPanel(contact)">{{contact.name}}<br>{{contact.group}}</ou-button>

                    
					</div>
				</div>


                </div>




                <ou-panel title='Create Contact' size='medium' v-model='createPanel'>
    				

                	<ou-text-field v-model='f_name' label='First Name' />

                	<ou-text-field v-model='l_name' label='Last Name' />

                	<ou-text-field v-model='phone' label='Phone Number' />


                	<label>Group</label>
                	
                	<select v-model="group_id" class="form-control">
                		
                		<option v-for="group in groups" :value="group.id">{{group.name}}</option>
                	
                	</select>

                	

                	<br>

                	<ou-button type='primary' @click="createContact()">Create</ou-button>

  				</ou-panel>


  				<ou-panel title='Import Contact' size='medium' v-model='importPanel'>
    				

                	

                	<label>Upload File (excel,csv)</label>
                	<input type="file" name="contact_file" ref="contact_file" v-on:change="handleFileUpload()" required>


                	<label>Group</label>
                	
                	<select v-model="import_group_id" class="form-control">
                		
                		<option v-for="group in groups" :value="group.id">{{group.name}}</option>
                	
                	</select>

                	

                	<br>

                	<ou-button type='primary' @click="importContact()">Import</ou-button>

  				</ou-panel>



  				 <ou-panel title='Manage Contact' size='medium' v-model='editPanel'>
    				
  				 	<ou-button type='primary' @click="deleteContact(contact.id)">Delete</ou-button>

  				 	

  				 	<hr>
                	<ou-text-field v-model='contact.f_name' label='First Name' />

                	<ou-text-field v-model='contact.l_name' label='Last Name' />

                	<ou-text-field v-model='contact.phone' label='Phone Number' />

                	<label>Group</label>
                	
                	<select v-model="contact.group_id" class="form-control">
                		
                		<option v-for="group in groups" :value="group.id">{{group.name}}</option>
                	
                	</select>

                	<br>

                	<ou-button type='primary' @click="updateContact(contact.id)">Update</ou-button>

  				</ou-panel>


            </div>

	</div>

</template>

<script type="text/javascript">
	
	export default {

		mounted(){

			this.getContacts()
			this.getGroups()
		},

		data(){

			return {

				contacts: [],
				groups: [],
				group_id: '',
				import_group_id: '',
				f_name: '',
				l_name: '',
				phone: '',
				email: '',
				contact: {},
				createPanel: false,
				editPanel: false,
				importPanel: false,
				contact_file: ''
			}
		},

		methods: {


			getGroups(){

				axios.get('/api/groups').then(response => {

					this.groups = response.data
					console.log(response)

				}).catch(error => {

					alert(error)
				})
			},

			getContacts(){

				axios.get('/api/contacts').then(response => {

					this.contacts = response.data
				}).catch(error => {

					alert(error)
				})
			},

			createContact(){

				let data = {

					f_name: this.f_name,
					l_name: this.l_name,
					phone: this.phone,
					group_id: this.group_id
				}

				axios.post('/api/contacts', data).then(response => {
					this.getContacts()
					this.createPanel = false
				}).catch(error => {
					alert(error)
				})
			},

			showCreatePanel(){

				this.createPanel = true
			},

			showImportPanel(){

				this.importPanel = true
			},

			showEditPanel(contact){
				this.contact = contact
				this.editPanel = true
			},


			updateContact(id){

				let data = {

					f_name: this.contact.f_name,
					l_name: this.contact.l_name,
					phone: this.contact.phone,
					group_id: this.contact.group_id
				}

				axios.put('/api/contacts/'+id, data).then(response => {
					this.getContacts()
					this.editPanel = false
				}).catch(error => {
					alert(error)
				})
			},


			deleteContact(id){

				
				axios.delete('/api/contacts/'+id).then(response => {
					this.getContacts()
					this.editPanel = false
				}).catch(error => {
					alert(error)
				})
			},


			handleFileUpload(){

				this.contact_file = this.$refs.contact_file.files[0];

				console.log(this.contact_file)
			},


			importContact(){
				

				let formData = new FormData()
				formData.append('contact_file', this.contact_file)
				formData.append('group_id', this.import_group_id)
				

				console.log(formData)

				axios.post('/api/contacts/import', formData, { headers: {'Content-Type': 'multipart/form-data' } }).then(response => {
					this.getContacts()
					this.importPanel = false

				}).catch(error =>{
					console.log(error)
					alert(error)

				})
			}



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