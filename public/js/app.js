// Comportamentos simples da interface.
document.addEventListener('DOMContentLoaded', () => {
    // Evita envio duplo de formulários (clique repetido no botão).
    document.querySelectorAll('form').forEach((form) => {
        form.addEventListener('submit', () => {
            form.querySelectorAll('button[type="submit"]').forEach((botao) => {
                botao.setAttribute('aria-busy', 'true');
                setTimeout(() => { botao.disabled = true; }, 0);
            });
        });
    });

    // Pede confirmação antes de ações destrutivas (botões com data-confirmar).
    document.querySelectorAll('[data-confirmar]').forEach((botao) => {
        botao.addEventListener('click', (evento) => {
            if (!window.confirm(botao.dataset.confirmar)) {
                evento.preventDefault();
            }
        });
    });
});

// Ao voltar pelo histórico do navegador, reabilita botões desativados.
window.addEventListener('pageshow', () => {
    document.querySelectorAll('button[aria-busy="true"]').forEach((botao) => {
        botao.disabled = false;
        botao.removeAttribute('aria-busy');
    });
});
