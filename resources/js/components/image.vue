<template>
  <v-file-input
    :rules="rules"
    accept="image/png, image/jpeg, image/bmp"
    placeholder="Pick an avatar"
    prepend-icon="mdi-camera"
    label="Avatar"
  ></v-file-input>
    
    <h2>Image view</h2>
      <v-file-input 
        :rules="rules"
        placeholder="Pick an avatar"  
        prepend-icon="mdi-camera"
        show-size accept="image/*" 
          label="Avatar">
      </v-file-input>
</template>
<script>
  export default {
    data: () => ({
      rules: [
        value => !value || value.size < 2000000 || 'Avatar size should be less than 2 MB!',
      ],
    }),
  }
</script>