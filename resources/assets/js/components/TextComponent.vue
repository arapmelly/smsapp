<template>
	
	<div>
		
			<div class="card">
                <div class="card-header">
                	
                	<ou-button type='primary' @click="showCreatePanel()">create</ou-button>
                	
                </div>

                <div class="card-body">
                    

                    <div class="row">
                    <div class="col-md-2 conts" v-for="text in texts" >

                    	<ou-button type='compound' :description='text.text' @click="showEditPanel(text)">{{text.name}}</ou-button>

                    
					</div>
				</div>


                </div>




                <ou-panel title='Create Text' size='medium' v-model='createPanel'>
    				

                	<ou-text-field v-model='name' label='Name' />

                	<ou-text-field type='multiline' v-model='sms' label='Text' />

                	

                	<br>

                	<ou-button type='primary' @click="createText()">Create</ou-button>

  				</ou-panel>



  				 <ou-panel title='Manage Text' size='medium' v-model='editPanel'>
    				
  				 	<ou-button type='primary' @click="deleteText(text.id)">Delete</ou-button>

  				 	

  				 	<hr>
                	<ou-text-field v-model='text.name' label='Name' />

                	<ou-text-field type='multiline' v-model='text.text' label='Text' />

                	

                	<br>

                	<ou-button type='primary' @click="updateText(text.id)">Update</ou-button>

  				</ou-panel>


            </div>

	</div>

</template>

<script type="text/javascript">
	
	export default {

		mounted(){

			this.getTexts()
		},

		data(){

			return {

				texts: [],
				name: '',
				sms: '',
				text: {},
				createPanel: false,
				editPanel: false
			}
		},

		methods: {

			getTexts(){

				axios.get('/api/texts').then(response => {

					this.texts = response.data
				}).catch(error => {

					alert(error)
				})
			},

			createText(){

				let data = {

					name: this.name,
					text: this.sms
				}

				axios.post('/api/texts', data).then(response => {
					this.getTexts()
					this.createPanel = false
				}).catch(error => {
					alert(error)
				})
			},

			showCreatePanel(){

				this.createPanel = true
			},

			showEditPanel(text){
				this.text = text
				this.editPanel = true
			},


			updateText(id){

				let data = {

					name: this.text.name,
					text: this.text.text
				}

				axios.put('/api/texts/'+id, data).then(response => {
					this.getTexts()
					this.editPanel = false
				}).catch(error => {
					alert(error)
				})
			},


			deleteText(id){

				
				axios.delete('/api/texts/'+id).then(response => {
					this.getTexts()
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