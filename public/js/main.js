hljs.initHighlightingOnLoad();

new Vue({
    el: "#app",
    data: {
        title: "Post title",
        author: "Pedro Sérgio",
        category_id: 1,
        key: "your key",
        body: "# welcome",
        categories: [],
        action: "posts",
        newCategoryName: "name your new category"
    },
    computed: {
        compiledMarkdown: function() {
            return marked(this.body, {
                sanitize: true,
                highlight: function(code) {
                return hljs.highlightAuto(code).value;
                }, 
            });
        }
    },
    methods: {
        update: _.debounce(function(e) {
            this.body = e.target.value;
                document.querySelectorAll('pre code').forEach((block) => {
                    hljs.highlightBlock(block);
                });
            }, 300),

            createCategory() {
                const { newCategoryName, key } = this;
                axios({
                    method: 'post',
                    url: '/api/category/create.php',
                    data: { name: newCategoryName, key }
                }).then(response => {
                    if (response.data === 'ACCESS DENIED') {
                        alert('incorrect input')
                    } else {
                        alert('category created')
                    }
                })
        },

        createPost() {
            const { title, author, category_id, body, key } = this;
            axios({
                method: 'post',
                url: '/api/post/create.php',
                data: {
                    title,
                    author,
                    category_id,
                    body,
                    key,
                }
            }).then(response => {
                if (response.data === 'ACCESS DENIED') {
                    alert('incorrect input')
                } else {
                    alert('post created')
                }
            })
        }
    },
    beforeMount() {
        axios({
            method: 'post',
            url: '/api/category/read.php',
        }).then(categories => {
            this.categories = categories.data.data
        })
    },
})