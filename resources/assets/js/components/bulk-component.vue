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

            </div>
		

	</div>
</template>
<script type="text/javascript">
	
	export default {

		mounted(){

			this.getTexts()
			this.getGroups()
			this.getOutboxes()

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
				outboxes: []
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


			getGroups(){

				axios.get('/api/groups').then(response => {

					this.groups = response.data
					

				}).catch(error => {

					alert(error)
				})
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