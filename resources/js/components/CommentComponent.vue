<template>
    <div class="container comments-container">
        <h4 v-if='!comments'>No comments at this moment.</h4>
        <ul  v-else v-for="comment in comments" v-bind:key='comment.id' class="media-list">
            <li v-if="comment" class="media">
                    <a class="pull-left" href="#">
                        <img class="comment-avatar media-object img-circle" :src="comment.user.avatar" alt="profile">
                    </a>
                    <div class="media-body">
                        <div class="well well-lg">
                            <button v-if="user && comment.user && user.id == comment.user.id" :id="'comment-' + comment.id" class="btn btn-danger"
                             @click="$emit('delete-comment')">
                                <span class="glyphicon glyphicon-trash"></span>
                            </button>
                            <h4 class="media-heading text-uppercase reviews">{{comment.user.name}}</h4>
                            <p>
                                <span>{{ comment.created_at | moment("dddd, MMMM Do YYYY, h:mm") }}</span>
                            </p>
                            <p class="media-comment">
                            {{comment.content}}
                            </p>
                            <a v-if="user && comment.id" class="btn btn-info btn-circle text-uppercase" href="#"
                             @click='replyTrigger' :id="'replay-to-' + comment.id"><span class="glyphicon glyphicon-share-alt"></span> Reply</a>

                            <a v-if="comment.children && _.size(comment.children) > 0" @click="showCommentChildren" class="btn btn-circle text-uppercase" :class="showChildCommentIds.includes(comment.id) ? 'btn-warning' : 'comments-button'"
                             data-toggle="collapse" href="#replyOne" :id="'expand-children-' + comment.id">
                                <span class="glyphicon glyphicon-comment"></span>
                                {{_.size(comment.children) + ' comments'}}
                                <span :class="'glyphicon glyphicon-arrow-' + (showChildCommentIds.includes(comment.id) ? 'up' : 'down')"></span>
                            </a>
                        </div>              
                    </div>
            </li>  

            <comment-form v-if="user && repliedCommentId == comment.id" @load-overlay-comment="toggleLoadOverlay"
                :csrf="csrf" :save-comment-route="saveCommentRoute" @new-comment="addComment" :user="user"
                :replied-comment-id="repliedCommentId" :id="'replay-form-' + comment.id"></comment-form>

            <slide-up-down :active="comment && comment['children'] && Object.keys(comment['children']).length != 0 && showChildCommentIds.includes(comment.id)" :duration="1500">
                <comment-component
                :comments="comment['children']" 
                :csrf="csrf" :save-comment-route="saveCommentRoute" :commentID="repliedCommentId"
                @delete-comment="deleteComment" :user="user" @load-overlay-comment="toggleLoadOverlay" @new-comment="addComment"
                @reply-to-comment="sendRepliedCommentId"></comment-component>
            </slide-up-down>

        </ul> 
    </div>
</template>

<script>
    import CommentForm from './CommentForm';
    export default {
        name : 'CommentComponent',
        components : { CommentForm }, 
        props : ['comments', 'user', 'saveCommentRoute', 'csrf', 'commentID'],
        data : function() {
            return {
                repliedCommentId : 0,
                showChildCommentIds : [],
            }
        },
        watch : {
            commentID() {
                this.repliedCommentId = this.commentID;
            },
        },
        methods : {
            deleteComment() {
                this.$emit('delete-comment');
            },
            addComment(data) {
                if(data['trail'])
                    Vue.set(this.showChildCommentIds, this.showChildCommentIds.length, parseInt(data['trail'][0]));
                this.$emit('new-comment', data);
            },
            toggleLoadOverlay(displayOverlay) {
                this.$emit('load-overlay-comment', displayOverlay);
            },
            sendRepliedCommentId(commentID) {
                this.repliedCommentId = commentID;
                this.$emit('reply-to-comment', commentID);
            },
            replyTrigger() {
                var replyTarget = event.target;
                if(replyTarget.getAttribute('id') == null && replyTarget.classList.contains('glyphicon')) {
                    replyTarget = replyTarget.parentElement;
                }
                this.repliedCommentId = replyTarget.getAttribute('id').split("-").pop();
                this.$emit('reply-to-comment', this.repliedCommentId);
                var options = {
                    easing: 'ease-in',
                    offset: -200,
                    force: true,
                    cancelable: true,
                    x: false,
                    y: true
                };
                VueScrollTo.scrollTo(replyTarget, 500, options);
            },
            showCommentChildren() {
                var showChildrenTarget = event.target;
                if(showChildrenTarget.getAttribute('id') == null && showChildrenTarget.classList.contains('glyphicon')) {
                    showChildrenTarget = showChildrenTarget.parentElement;
                }
                var toggleChildrenFor = parseInt(showChildrenTarget.getAttribute('id').split("-").pop());
                var toggleChildrenForIndex = this.showChildCommentIds.indexOf(toggleChildrenFor);
                if(toggleChildrenForIndex == -1)
                    Vue.set(this.showChildCommentIds, this.showChildCommentIds.length, toggleChildrenFor);
                else
                    Vue.delete(this.showChildCommentIds, toggleChildrenForIndex);
            }
        }
    }
</script>
