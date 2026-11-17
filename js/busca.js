'use strict';

const formularioBusca = document.getElementById('form-busca');
const campoBusca = document.getElementById('campo-busca');
const areaResultados = document.getElementById('resultados');

if (formularioBusca && campoBusca && areaResultados) {
    let temporizadorBusca;
    let controladorBusca;

    function ocultarResultados() {
        clearTimeout(temporizadorBusca);
        if (controladorBusca) {
            controladorBusca.abort();
            controladorBusca = null;
        }

        areaResultados.hidden = true;
        areaResultados.textContent = '';
        areaResultados.setAttribute('aria-busy', 'false');
    }

    async function pesquisar() {
        if (controladorBusca) {
            controladorBusca.abort();
            controladorBusca = null;
        }

        controladorBusca = new AbortController();
        const controladorAtual = controladorBusca;
        areaResultados.hidden = false;
        areaResultados.textContent = 'Pesquisando...';
        areaResultados.setAttribute('aria-busy', 'true');

        try {
            const resposta = await fetch(formularioBusca.action, {
                method: 'POST',
                body: new FormData(formularioBusca),
                signal: controladorAtual.signal,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (!resposta.ok) {
                throw new Error('Falha ao pesquisar notícias.');
            }

            const html = await resposta.text();
            if (controladorBusca === controladorAtual) {
                areaResultados.innerHTML = html;
            }
        } catch (erro) {
            if (erro.name !== 'AbortError' && controladorBusca === controladorAtual) {
                areaResultados.textContent = 'Não foi possível realizar a pesquisa.';
            }
        } finally {
            if (controladorBusca === controladorAtual) {
                areaResultados.setAttribute('aria-busy', 'false');
                controladorBusca = null;
            }
        }
    }

    function iniciarPesquisa() {
        clearTimeout(temporizadorBusca);
        const termo = campoBusca.value.trim();

        if (termo.length < 2) {
            ocultarResultados();
            return;
        }

        if (controladorBusca) {
            controladorBusca.abort();
            controladorBusca = null;
        }

        areaResultados.hidden = false;
        areaResultados.textContent = 'Pesquisando...';
        areaResultados.setAttribute('aria-busy', 'true');
        temporizadorBusca = setTimeout(pesquisar, 300);
    }

    campoBusca.addEventListener('input', iniciarPesquisa);
    formularioBusca.addEventListener('submit', event => {
        event.preventDefault();
        clearTimeout(temporizadorBusca);
        if (campoBusca.value.trim().length >= 2) {
            pesquisar();
        }
    });

    campoBusca.addEventListener('keydown', event => {
        if (event.key === 'Escape') {
            ocultarResultados();
        }
    });

    document.addEventListener('click', event => {
        if (!formularioBusca.contains(event.target) && !areaResultados.contains(event.target)) {
            ocultarResultados();
        }
    });
}

