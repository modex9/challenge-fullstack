<template>
    <div class="container">
        <h4 v-if='!comments && !isChild'>No comments at this moment.</h4>
        <div  v-else v-for="comment in comments" v-bind:key='comment.id' class="wrapper">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">{{comment.user.name}}</div>
                        <div class="card-body">
                            <p class="card-text">{{'ID : ' + comment.id}}</p>
                            <p class="card-text">{{"parent ID: " + comment.parent}}</p>
                            {{comment.content}}
                        </div>
                    </div>
                </div>
            </div>
            <comment-component v-if="comment['children']" :comments="comment['children']" :is-child="true"></comment-component>
        </div>
    </div>
</template>

<script>
    export default {
        name : 'CommentComponent',
        props : ['comments', 'isChild'],
        mounted() {
            this.count = this.c;
        },
    }
</script>
