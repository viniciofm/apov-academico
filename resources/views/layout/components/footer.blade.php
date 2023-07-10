<footer class="footer">
    <div class="container-fluid">
        <div class="row text-muted">
            <div class="col-8 text-start">
                <ul class="list-inline">
                    <li class="list-inline-item">
                        <a class="text-muted" href="<?php echo env('APP_URL'); ?>#contato" target="_blank">Suporte Técnico</a>
                    </li>
                    <li class="list-inline-item">
                        <a class="text-muted" href="<?php echo env('APP_URL'); ?>/public/files/politica-de-privacidade.pdf" target="_blank">Política de Uso</a>
                    </li>
                    <li class="list-inline-item">
                        <a class="text-muted" href="<?php echo env('APP_URL'); ?>/public/files/termo-de-uso.pdf" target="_blank">Termos de Privacidade</a>
                    </li>
                    <li class="list-inline-item">
                        <a class="text-muted" href="<?php echo env('APP_URL'); ?>#contato" target="_blank">Contato</a>
                    </li>

                    <li class="list-inline-item">
                        <a class="text-muted text-italic" href="<?php echo env('APP_URL'); ?>" title="Versão do sistema" target="_blank"> Versão: 06/2023  </a>
                    </li>
                </ul>
            </div>
            <div class="col-4 text-end">
                <p class="mb-0">
                    <i class="fa-brands fa-rebel"></i> {{now()->year}} - <a href="https://github.com/viniciofm" target="_blank" class="text-muted">Desenvolvido por Vinício F. Mendes</a>
                </p>
            </div>
        </div>
    </div>
</footer>
