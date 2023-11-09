<template>
  <div class="modal">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">{{ createModalTitle }}</h5>
      </div>
      <div class="modal-body">
        <component :is="customFormComponent" :modalMode="modalMode"></component>
        <!-- <form-add-vue></form-add-vue> -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary">Save changes</button>
        <button type="button" class="btn btn-secondary" @click="this.$emit('close-modal')">Close</button>
      </div>
    </div>
  </div>
</template>

<script>
import CustomForm from '../form/CustomForm.vue'

export default {
  components: {
    CustomForm
  },

  computed: {
    createModalTitle() {
      return this.modalMode == 'edit'
        ? 'Edit' + ' ' + this.model
        : 'Add' + ' ' + this.model
    }

  },
  props: {
    model: {
      type: String,
      default: "default model"
    },
    customFormComponent: {
      type: String,
      default: null
    },
    modalMode: {
      type: String,
      default: "add"
    },
  },
  emits: ['close-modal'],
}

</script>

<style scoped>
/* Custom modal styles */
.modal {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: rgba(0, 0, 0, 0.5);
  /* Semi-transparent background */
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000;
}

.modal-content {
  background-color: #fff;
  border-radius: 5px;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
  width: 80%;
  max-width: 600px;
  position: relative;
}
</style>
