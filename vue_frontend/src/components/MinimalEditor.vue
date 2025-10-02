<template>
  <div class="editor">
    <div class="editor-toolbar">
      <button :class="btnClass('bold')" @click="cmd('toggleBold')"><b>B</b></button>
      <button :class="btnClass('italic')" @click="cmd('toggleItalic')"><i>I</i></button>
      <button :class="btnClass('strike')" @click="cmd('toggleStrike')"><s>S</s></button>
      <span class="divider" />
      <button :class="btnClass('bulletList')" @click="cmd('toggleBulletList')">• List</button>
      <button :class="btnClass('orderedList')" @click="cmd('toggleOrderedList')">1. List</button>
      <span class="divider" />
      <button :class="btnClass('heading', { level: 2 })" @click="cmd('toggleHeading', { level: 2 })">H2</button>
      <button :class="btnClass('heading', { level: 3 })" @click="cmd('toggleHeading', { level: 3 })">H3</button>
      <span class="divider" />
      <button class="tool-btn" @click="editor?.chain().focus().undo().run()" :disabled="!editor?.can().undo()">Undo</button>
      <button class="tool-btn" @click="editor?.chain().focus().redo().run()" :disabled="!editor?.can().redo()">Redo</button>
      <button class="tool-btn" @click="clearContent">Clear</button>
    </div>
    <div class="editor-content" :class="{ 'is-empty': isEmpty }">
      <EditorContent v-if="editor" :editor="editor" />
      <div v-if="isEmpty && placeholder" class="placeholder">{{ placeholder }}</div>
    </div>
  </div>
</template>

<script>
import { Editor, EditorContent } from '@tiptap/vue-2'
import StarterKit from '@tiptap/starter-kit'

export default {
  name: 'MinimalEditor',
  components: { EditorContent },
  props: {
    value: { type: String, default: '' },
    placeholder: { type: String, default: '' }
  },
  data() {
    return { editor: null }
  },
  computed: {
    isEmpty() {
      if (!this.editor) return !this.value
      return this.editor.isEmpty
    }
  },
  mounted() {
    this.editor = new Editor({
      content: this.value || '',
      extensions: [StarterKit],
      onUpdate: ({ editor }) => {
        this.$emit('input', editor.getHTML())
      }
    })
  },
  beforeDestroy() {
    if (this.editor) this.editor.destroy()
  },
  methods: {
    cmd(name, args = {}) {
      if (!this.editor) return
      this.editor.chain().focus()[name](args).run()
    },
    btnClass(name, args = {}) {
      const active = this.editor?.isActive(name, args)
      return ['tool-btn', { active }]
    },
    clearContent() {
      this.editor?.commands.clearContent(true)
    }
  },
  watch: {
    value(newVal) {
      if (!this.editor) return
      // Avoid loop: only set if different from current html
      if (newVal !== this.editor.getHTML()) {
        this.editor.commands.setContent(newVal || '', false)
      }
    }
  }
}
</script>

<style scoped>
.editor { border: 1px solid #d0d0d0; border-radius: 6px; background: #fff; }
.editor-toolbar { display: flex; flex-wrap: wrap; gap: 6px; padding: 8px; border-bottom: 1px solid #e5e5e5; }
.tool-btn { padding: 6px 10px; border: 1px solid #ddd; background: #fafafa; border-radius: 4px; cursor: pointer; font-size: 12px; }
.tool-btn.active { background: #ffe9f1; border-color: #ff6b9d; color: #ff3e7e; }
.tool-btn:disabled { opacity: 0.5; cursor: not-allowed; }
.divider { width: 1px; height: 24px; background: #eee; margin: 0 4px; }
.editor-content { position: relative; padding: 10px 12px; min-height: 160px; }
.placeholder { position: absolute; top: 10px; left: 12px; color: #aaa; pointer-events: none; }
/* TipTap content basics */
.editor-content /deep/ .ProseMirror { outline: none; }
.editor-content /deep/ .ProseMirror p { margin: 8px 0; }
.editor-content /deep/ .ProseMirror h2 { font-size: 1.25rem; margin: 12px 0 6px; }
.editor-content /deep/ .ProseMirror h3 { font-size: 1.1rem; margin: 10px 0 6px; }
.editor-content /deep/ ul, .editor-content /deep/ ol { padding-left: 1.25rem; }
</style>
