<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="/public/css/styles.css">
    
    <script src="https://unpkg.com/vue"></script>
    <script src="https://unpkg.com/marked@0.3.6"></script>
    <script src="https://unpkg.com/lodash@4.16.0"></script>

    <link rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/10.3.2/styles/sunburst.min.css">
    <!-- CSS -->
    <link rel="stylesheet" href="https://bootswatch.com/4/<?= $_POST['t'] ?? 'lux' ?>/bootstrap.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/10.3.2/highlight.min.js"></script> 
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.21.0/axios.min.js"></script>

    <title>Create a post</title>
</head>
<body>
    <div id="app" class="container">
      <br>
      <div class="row">
        <div class="col">
          <h1>create post</h1>
        </div>
        <div class="col">
          <select id="theme" class="form-control">
            <option value="" disabled selected>Choose a theme</option>
            <option v-for="theme in themes" @click="switchThemeTo(theme)" :value="theme">{{ theme }}</option>
          </select>
        </div>
      </div>
      <div class="row">
        <div class="form-group col">
          <label for="title">Title</label>
          <input placeholder="title" id="title" :value="title" type="text" class="form-control">
        </div>
        <div class="form-group col">
          <label for="title">Author</label>
          <input placeholder="author" id="author" :value="author" type="text" class="form-control">
        </div>
      </div>
      <div class="row">
        <div class="input-field col s6">
          <label for="category">Category</label>
          <select id="category" class="form-control">
            <option value="" disabled selected>Choose your option</option>
            <option v-for="category in categories" :value="category.id">{{ category.name }}</option>
          </select>
        </div>
        <div class="input-field col s6">
          <label for="title">Password</label>
          <input placeholder="password" id="password" :value="key" type="password" class="form-control">
        </div>
      </div>
  
      <br>
      <div class="row">
        <div class="col">
          <textarea 
            style="height: 50vh; resize: none;" 
            class="input form-control" 
            :value="body" 
            @input="update"></textarea>
        </div>
        <div class="col">
          <div style="height: 50vh; overflow: auto;" v-html="compiledMarkdown"></div>
        </div>
      </div>

      <br>  
      <div>
        <button @click="send" class="btn btn-success">Create</button>
      </div>
    </div>
<script>

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
            themes: [
              'lux','darkly','spacelab','materia','cerulean',
              'litera','sandstone','slate','superhero',
              'cosmo','flatly','lumen','minty',
              'simplex','solar','united','cyborg',
              'journal','pulse','sketchy','yeti'
            ]
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

          switchThemeTo(theme) {
            const form = document.createElement("form");
            const themeName = document.createElement("input"); 
            
            form.method = "POST";
            form.action = "#";   
            
            themeName.value = theme;
            themeName.name = "t";

            form.appendChild(themeName);  
            document.body.appendChild(form);
            form.submit();
          },
          send() {
            const {
              title,
              author,
              category_id,
              body,
              key
            } = this;

            axios({
              method: 'post',
              url: '/api/post/create.php',
              data: {
                title,
                author,
                category_id,
                body,
                key
              }
            }).then(_ => alert('post created'))
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
</script>
</body>
</html>