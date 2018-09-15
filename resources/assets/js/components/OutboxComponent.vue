<template>
	
	<div>
		
			<div class="card">
                <div class="card-header">
                	
                	<ou-button type='primary' @click="showCreatePanel()">Bulk</ou-button>

                	<ou-button type='primary' @click="showCreateSinglePanel()">Single</ou-button>
                	
                </div>

                <div class="card-body">
                    

                    <div class="row">
                    <div class="col-md-2 conts" v-for="outbox in outboxes" >

                    	<ou-button type='compound' :description='outbox.text' @click="showEditPanel(outbox)">{{outbox.group.name}}<br/>{{outbox.send_date}} <br>{{outbox.send_time}}</ou-button>

                    
					</div>
				</div>


                </div>





                <ou-panel title='Create Outbox' size='lg' v-model='createSinglePanel'>
    				

                	


                	<ou-text-field type='multiline' v-model='sms' label='Text' />
                	 <ou-link href='#' @click="showSMSTemplates()">* import From template</ou-link>
                	 <br><br>

                	 <ou-text-field v-model='contact' label='Phone Number' />
                	
                	

          
                	

                	<br>

                	<ou-button type='primary' @click="createSingleOutbox()">Create</ou-button>



                	<ou-dialog type='multiline' title='SMS Templates' v-model='smsTemplate'>
    				
    					<span v-for="text in texts" style="padding:5px;">
    						<ou-button type='compound' @click='setText(text)' :description='text.text'></ou-button>

    					</span>

    
  					</ou-dialog>

  				</ou-panel>





                <ou-panel title='Create Outbox' size='lg' v-model='createPanel'>
    				

                	


                	<ou-text-field type='multiline' v-model='sms' label='Text' />
                	 <ou-link href='#' @click="showSMSTemplates()">* import From template</ou-link>
                	 <br><br>


                	<label>Date & Time</label>
                	<vue-ctk-date-time-picker v-model="send_date"  :minute-interval="-10" color="#005a9e" enable-button-validate format="YYYY-MM-DD H:MM:SS"
            ></vue-ctk-date-time-picker>


            <label>Group</label>
                	
                	<select v-model="group_id" class="form-control">
                		
                		<option v-for="group in groups" :value="group.id">{{group.name}}</option>
                	
                	</select>
                	

                	

                	

                	<br>

                	<ou-button type='primary' @click="createOutbox()">Create</ou-button>



                	<ou-dialog type='multiline' title='SMS Templates' v-model='smsTemplate'>
    				
    					<span v-for="text in texts" style="padding:5px;">
    						<ou-button type='compound' @click='setText(text)' :description='text.text'></ou-button>

    					</span>

    
  					</ou-dialog>

  				</ou-panel>



  				 <ou-panel title='Manage Outbox' size='medium' v-model='editPanel'>


    				
  				 	<ou-button type='primary' @click="deleteOutbox(outbox.id)">Delete</ou-button>

  				 	

  				 	<hr>
                	

                	<ou-text-field type='multiline' v-model='outbox.text' label='Text' />

                	<ou-text-field v-model='outbox.send_date' label='Send Date' />

                	<ou-text-field v-model='outbox.send_time' label='Send Time' />

                	<label>Group</label>
                	
                	<select v-model="outbox.group_id" class="form-control">
                		
                		<option v-for="group in groups" :value="group.id">{{group.name}}</option>
                	
                	</select>
                	

                	<br>

                	<ou-button type='primary' @click="updateOutbox(outbox.id)">Update</ou-button>

  				</ou-panel>



  				


            </div>

	</div>

</template>

<script type="text/javascript">
	
	export default {

		mounted(){

			this.getTexts()
			this.getOutboxes()
			this.getGroups()
		},

		data(){

			return {

				outboxes: [],
				groups: [],
				group_id: '',
				texts: [],
				send_date: '',
				send_time: '',
				sms: '',
				outbox: {},
				createPanel: false,
				editPanel: false,
				smsTemplate: false,
				createSinglePanel: false
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

			getGroups(){

				axios.get('/api/groups').then(response => {

					this.groups = response.data
					console.log(response)

				}).catch(error => {

					alert(error)
				})
			},

			getOutboxes(){

				axios.get('/api/bulks').then(response => {

					this.outboxes = response.data
				}).catch(error => {

					alert(error)
				})
			},

			createOutbox(){

				let data = {

					text: this.sms,
					send_date: this.send_date,
					group_id: this.group_id
				}

				axios.post('/api/bulks', data).then(response => {
					this.getOutboxes()
					this.createPanel = false
				}).catch(error => {
					alert(error)
				})
			},

			showSMSTemplates(){
				this.smsTemplate = true;
			},

			setText(text){
				this.sms = text.text
				this.smsTemplate = false
			},

			showCreatePanel(){

				this.createPanel = true
			},

			showEditPanel(outbox){
				this.outbox = outbox
				this.editPanel = true
			},

			showCreateSinglePanel(){

				this.createSinglePanel = true
			},


			updateOutbox(id){

				let data = {

					text: this.outbox.text,
					send_date: this.outbox.send_date,
					send_time: this.outbox.send_time,
					group_id: this.outbox.group_id
				}

				axios.put('/api/bulks/'+id, data).then(response => {
					this.getOutboxes()
					this.editPanel = false
				}).catch(error => {
					alert(error)
				})
			},


			deleteOutbox(id){
				
				axios.delete('/api/bulks/'+id).then(response => {
					this.getOutboxes()
					this.editPanel = false
				}).catch(error => {
					alert(error)
				})
			},


			createSingleOutbox(){

				let data = {

					text: this.sms,
					contact: this.contact

				}

				axios.post('/api/outboxes/single', data).then(response => {
					this.getOutboxes()
					this.createSinglePanel = false
					alert('sms has been successfully sent');
				}).catch(error => {
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