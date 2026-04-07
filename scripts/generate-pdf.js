import puppeteer from 'puppeteer';

const url = process.argv[2]; // URL iz Laravel kontrolera
if (!url) {
    console.error("Nije prosleđen URL!");
    process.exit(1);
}

(async () => {
    const browser = await puppeteer.launch({ headless: true });
    const page = await browser.newPage();

    await page.goto(url, { waitUntil: 'networkidle0' });

    const a4Element = await page.$('.a4-wrapper');
    if (!a4Element) {
        console.error('Div .a4-wrapper nije pronađen!');
        await browser.close();
        process.exit(1);
    }

    await a4Element.pdf({
        path: 'invoice.pdf',
        format: 'A4',
        printBackground: true,
        margin: { top: '10mm', right: '10mm', bottom: '10mm', left: '10mm' }
    });

    console.log('✅ PDF generisan: invoice.pdf');
    await browser.close();
})();