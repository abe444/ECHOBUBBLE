function reply_2_reply(post) {
    const textareas = document.getElementsByName("message");
    const textarea = textareas[0];

    textarea.value += ">>" + post + "\n";
    textarea.focus();
}
