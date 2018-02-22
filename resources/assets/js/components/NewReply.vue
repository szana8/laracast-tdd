<template>
    <div class="mt-4" v-if="signedIn">
        <div class="form-group">
            <textarea class="form-control"
                      name="body"
                      id="body"
                      rows="5"
                      placeholder="Have you say something?"
                      required
                      v-model="body">
            </textarea>
        </div>

        <button class="btn btn-xs"
                @click="addReply">Post</button>

    </div>

    <p class="text-center" v-else>Please <a href="/login">sign in</a> to participate this issue!</p>

</template>

<script>
    export default {
        props: ['endpoint'],

        data() {
            return {
                body: ''
            }
        },

        computed: {
            signedIn() {
                return window.App.signedIn;
            }
        },

        methods: {
            addReply() {
                axios.post(location.pathname + '/replies', {body: this.body})
                    .catch(error => {
                        flash(error.response.data, 'danger');
                    })
                    .then(data => {
                        this.body = '';

                        flash('Your reply has been posted.');

                        this.$emit('created', data.data);
                    });
            }
        }
    }
</script>
