<template>
  <div class="ndoTap">
      <div class="title">
        <img src="logo.jpeg" width="150">
      </div>
      <div class="label">
        {{ label }}
      </div>
    <div class="screenshotcontainer">
      <img class="screenshot"
          ref="image"
          :src="imageSource"
          alt="screenshot"
          @click="onClickImage"
      />
    </div>
      <div class="result">
        <i>"What's this?"</i><br>You are presented with a short label and a screenshot, where you can click or tap once.<br>Imagine yourself trying to achieve what the label describes, and tap on the screenshot.<br>Follow your first instinct!
      </div>
      <div class="result">
        {{ index }} / {{ max }}
      </div>
      <div class="result">
        <p id="coord" v-html="x != '' ? `${x}, ${y}`: ''"></p>
      </div>
      <div class="result">
        Made by Federico Matteoni - <a href="https://fexed.github.io">fexed.github.io</a>
      </div>
  </div>
</template>

<style>
  @import 'style.css';
</style>

<script lang="ts">
  import { defineComponent } from "vue";
  import axios from "axios";

  const imgs = ["screens/1"]
  const labels = ["label"]
  let ix = 0
  let imgname = imgs[ix]
  let label = labels[ix]
  const storagePrefix = "t4_interacted_"

  export default defineComponent({
    name: "IndexPage",
    data() {
      return {
        x: '',
        y: '',
        tapped: (window.localStorage.getItem(storagePrefix + imgname) || "") === "true",
        imageSource: imgname + ((window.localStorage.getItem(storagePrefix + imgname) || "") === "true" ? '_heatmap.png' : '.png'),
        label: ((window.localStorage.getItem(storagePrefix + imgname) || "") === "true" ? "Come back another time!" : label),
        index: ix + 1,
        max: imgs.length
      };
    },
    methods: {
      onClickImage(event: MouseEvent) {
        if (!this.$data.tapped) {
          this.$data.tapped = true
          this.$data.imageSource = imgname + "_heatmap.png"
          const target = event.target;
          const bounds = target.getBoundingClientRect();
          const img = new Image();
          img.src = imgname + '.png';
          const scale = this.$refs.image.width / img.width
          console.log(scale)
          let posX = (event.clientX - bounds.x)/scale;
          let posY = (event.clientY - bounds.y)/scale;
          posX = Math.floor(posX)
          posY = Math.floor(posY)
          console.log(posX, posY);
          const data = JSON.stringify(posX + " " + posY);
          window.localStorage.setItem('arr', data);
          
          imgname.replaceAll("/", "%2F");
          axios.post('http://localhost:4000/save.php?name=' + imgname, {x: posX, y: posY}).then(response => {
            console.log(response.data)
            this.$data.x = "" + posX
            this.$data.y = "" + posY
            //window.localStorage.setItem(storagePrefix + imgname, "true")

            if (ix < imgs.length) {
              ix = ix + 1
              imgname = imgs[ix]
              label = labels[ix]
              this.$data.imageSource = imgname + '.png'
              this.$data.label = label
              this.$data.tapped = false
              this.$data.index = ix + 1
            } else {
              this.$data.imageSource = imgname + "_heatmap.png"
              this.$data.label = "Thanks! No more images left!"
            }
          }).catch(e => {
            console.log(e)
            this.$data.x = "error"
            this.$data.y = "error"
          });
        }
      },
    },
  });
</script>
