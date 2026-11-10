// Comportamentos da interface. Tudo é opcional: o site funciona sem JavaScript.
document.addEventListener('DOMContentLoaded', () => {
    // Menu do celular.
    const alternar = document.querySelector('.alternar-menu');
    const topo = document.getElementById('topo');
    if (alternar && topo) {
        alternar.addEventListener('click', () => {
            const aberto = topo.classList.toggle('menu-aberto');
            alternar.setAttribute('aria-expanded', String(aberto));
        });
    }

    // Quantidade com botões + e −.
    document.querySelectorAll('[data-quantidade]').forEach((bloco) => {
        const campo = bloco.querySelector('input[type="number"]');
        bloco.querySelectorAll('[data-passo]').forEach((botao) => {
            botao.addEventListener('click', () => {
                const min = Number(campo.min) || 1;
                const max = Number(campo.max) || 99;
                const novo = (Number(campo.value) || min) + Number(botao.dataset.passo);
                campo.value = Math.min(max, Math.max(min, novo));
                campo.dispatchEvent(new Event('change', { bubbles: true }));
            });
        });
    });

    // Evita envio duplo de formulários (clique repetido no botão).
    document.querySelectorAll('form').forEach((form) => {
        form.addEventListener('submit', () => {
            form.querySelectorAll('button[type="submit"]').forEach((botao) => {
                botao.setAttribute('aria-busy', 'true');
                setTimeout(() => { botao.disabled = true; }, 0);
            });
        });
    });

    // Confirmação antes de ações destrutivas (botões com data-confirmar).
    document.querySelectorAll('[data-confirmar]').forEach((botao) => {
        botao.addEventListener('click', (evento) => {
            if (!window.confirm(botao.dataset.confirmar)) {
                evento.preventDefault();
            }
        });
    });

    // Janela de avaliação (usa o elemento <dialog> do navegador).
    document.querySelectorAll('[data-abrir-janela]').forEach((botao) => {
        const janela = document.getElementById(botao.dataset.abrirJanela);
        if (janela && typeof janela.showModal === 'function') {
            botao.addEventListener('click', () => janela.showModal());
        }
    });
    document.querySelectorAll('dialog.janela').forEach((janela) => {
        janela.querySelectorAll('[data-fechar-janela]').forEach((b) => b.addEventListener('click', () => janela.close()));
        janela.addEventListener('click', (evento) => { if (evento.target === janela) janela.close(); });
        if (janela.hasAttribute('data-abrir') && typeof janela.showModal === 'function') janela.showModal();
    });
});

// Ao voltar pelo histórico do navegador, reabilita botões desativados.
window.addEventListener('pageshow', () => {
    document.querySelectorAll('button[aria-busy="true"]').forEach((botao) => {
        botao.disabled = false;
        botao.removeAttribute('aria-busy');
    });
});
