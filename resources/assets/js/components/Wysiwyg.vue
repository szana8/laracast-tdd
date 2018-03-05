<template>
    <div>
        <input type="hidden" id="trix" class="trix-content" :name="name" :value="value">

        <trix-editor ref="trix" class="trix-content" input="trix" :placeholder="placeholder"></trix-editor>
    </div>
</template>

<script>
    import Trix from 'trix'

    export default {
        props: ['name', 'value', 'placeholder', 'shouldClear'],

        mounted() {
              this.$refs.trix.addEventListener('trix-change', e => {
                    this.$emit('input', e.target.innerHTML);
              });

              this.$watch('shouldClear', () => {
                 this.$refs.trix.value = '';
              });
        },
    }
</script>
