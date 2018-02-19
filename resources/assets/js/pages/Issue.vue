<script>
    import Replies from '../components/Replies'

    export default {
        name: "issue",

        props: ['initialRepliesCount'],

        data() {
            return {
                repliesCount: this.initialRepliesCount
            }
        },

        components: {
            Replies
        }
    }
</script>
