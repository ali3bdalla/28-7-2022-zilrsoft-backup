import VueHtml2pdf from 'vue-html2pdf'
 
export default {
    methods: {
        /*
            Generate Report using refs and calling the
            refs function generatePdf()
        */
        generateReport () {
            this.$refs.html2Pdf.generatePdf()
        }
    },
 
    components: {
        VueHtml2pdf
    }
}