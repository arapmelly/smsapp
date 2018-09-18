<template>
	
	<div>


			<div class="card">
                <div class="card-header">
                	
                	<ou-button type='primary' @click="showCreatePanel()">Bulk</ou-button>

                	<ou-button type='primary' @click="showCreateSinglePanel()">Single</ou-button>
                	
                </div>

                <div class="card-body">

                	<ou-pivot type='tabs'>
  						<ou-pivot-item label='Bulk Outbox'>

  							<div class="row">
                    			<div class="col-md-2 conts" v-for="bulk in bulks" style="padding:10px" >

                    				<ou-button type='compound' :description='bulk.text' @click="showEditPanel(bulk)">{{bulk.group_id}}<br/>{{bulk.send_date}} <br>{{bulk.send_time}}</ou-button>

                    
								</div>
							</div>

  							
  						</ou-pivot-item>
  						<ou-pivot-item label='Single Outbox'>


  							<div class="row">
                    			<div class="col-md-2 conts" v-for="outbox in outboxes" style="padding:10px" >

                    				<ou-button type='compound' :description='outbox.text' @click="showEditPanel(outbox)">{{outbox.contact}}<br/>{{outbox.send_date}} <br>{{outbox.send_time}}</ou-button>

                    
								</div>
							</div>

  							
  						</ou-pivot-item>
  						
					</ou-pivot>


                </div>



                <ou-panel title='Create Outbox' size='lg' v-model='createSinglePanel'>
    				

                	


                	<ou-text-field type='multiline' v-model='sms' label='Text' />
                	 <ou-link href='#' @click="showSMSTemplates()">* import From template</ou-link>
                	 <br><br>

                	 <ou-text-field v-model='phone' label='Phone Number' />
                	
                	

          
                	

                	<br>

                	<ou-button type='primary' @click="createSingleOutbox()" >Create</ou-button>

                	

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
                	<vue-ctk-date-time-picker v-model="send_date" :min-date="minDate"  color="#005a9e" enable-button-validate format="YYYY-MM-DD HH:mm:ss"
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

            </div>
		

	</div>
</template>
<script type="text/javascript">
	
	export default {

		mounted(){

			this.getTexts()
			this.getGroups()
			this.getOutboxes()
			this.getBulks()

		},

		data(){

			return {

				texts: [],
				groups: [],
				bulks: [],
				phone: '',
				sms: '',
				createPanel: false,
				createSinglePanel: false,
				smsTemplate: false,
				outboxes: [],
				send_date: '',
				minDate: new Date(Date.now() - 86400000),
				group_id: '',
				bulk: {}
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


			getOutboxes(){

				axios.get('/api/outboxes').then(response => {

					this.outboxes = response.data
				}).catch(error => {

					alert(error)
				})

			},


			getBulks(){

				axios.get('/api/bulks').then(response => {

					this.bulks = response.data
				}).catch(error => {

					alert(error)
				})

			},


			getGroups(){

				axios.get('/api/groups').then(response => {

					this.groups = response.data
					

				}).catch(error => {

					alert(error)
				})
			},

			showCreatePanel(){

				

				this.createPanel = true
			},


			showCreateSinglePanel(){

				this.createSinglePanel = true;
			},


			showSMSTemplates(){
				this.smsTemplate = true;
			},

			setText(text){
				this.sms = text.text
				this.smsTemplate = false
			},


			createOutbox(){

				let data = {

					text: this.sms,
					send_date: this.send_date,
					group_id: this.group_id
				}

				axios.post('/api/bulks', data).then(response => {
					this.getBulks()
					this.createPanel = false
				}).catch(error => {
					alert(error)
				})
			},



			createSingleOutbox(){

				let data = {

					text: this.sms,
					contact: this.phone

				}

				axios.post('/api/outboxes/single', data).then(response => {
					//this.getOutboxes()
					
					alert('sms has been successfully sent');
					this.createSinglePanel = false
					
				}).catch(error => {
					alert(error)
				})

			}
		}
	}
</script>