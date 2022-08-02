<footer class="footer">
    <div class="container-fluid">
        <div class="row text-muted">
            <div class="col-8 text-start">
                <ul class="list-inline">
                    <li class="list-inline-item">
                        <a class="text-muted" href="https://www.grosafety.com.br/#contato" target="_blank">Suporte Técnico</a>
                    </li>
                    <li class="list-inline-item">
                        <a class="text-muted" href="<?php echo env('APP_URL'); ?>/public/files/politica-de-privacidade.pdf" target="_blank">Politica de Uso</a>
                    </li>
                    <li class="list-inline-item">
                        <a class="text-muted" href="<?php echo env('APP_URL'); ?>/public/files/termo-de-uso.pdf" target="_blank">Termos de Privacidade</a>
                    </li>
                    <li class="list-inline-item">
                        <a class="text-muted" href="https://www.grosafety.com.br/#contato" target="_blank">Contato</a>
                    </li>

                    <li class="list-inline-item">

                        <a class="text-muted text-italic" href="https://www.ducortech.com.br/category/grosafety/" title="Veja as alterações realizadas" target="_blank"> Versão: 23/06/2022 - 15:00:00  </a>

                    </li>
                </ul>
            </div>
            <div class="col-4 text-end">
                <p class="mb-0">
                    <i class="fa-brands fa-rebel"></i> {{now()->year}} - <a href="https://github.com/VinicioFragosoMendes" target="_blank" class="text-muted">Desenvolvido por VFMENDES</a>
                </p>
            </div>
        </div>
    </div>
</footer>
