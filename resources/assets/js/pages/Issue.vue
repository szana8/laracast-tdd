<script>
    import Replies from '../components/Replies'
    import SubscribeButton from '../components/SubscribeButton'

    export default {
        name: "issue",

        props: ['issue'],

        components: {
            Replies,
            SubscribeButton
        },

        data() {
            return {
                repliesCount: this.issue.replies_count,
                locked: this.issue.locked
            }
        },

        methods: {
            toggleLock() {
                axios[this.locked ? 'delete' : 'post']('/locked-issues/' + this.issue.slug);

                this.locked = ! this.locked;
            }
        }
    }
</script>

