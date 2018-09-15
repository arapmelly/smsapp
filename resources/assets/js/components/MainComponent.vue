<template>

	<div>


		<ou-pivot type='tabs'>
  			<ou-pivot-item label='Outbox'>
  				<outbox-component></outbox-component>
  			</ou-pivot-item>
  			<ou-pivot-item label='Contacts'>
  				<contact-component></contact-component>
  			</ou-pivot-item>
        <ou-pivot-item label='Groups'>
          <group-component></group-component>
        </ou-pivot-item>
  			<ou-pivot-item label='Templates'>
  				<text-component></text-component>
  			</ou-pivot-item>
        
		</ou-pivot>

		
	</div>
</template>

<script type="text/javascript">
	
	export default {


	}
</script>