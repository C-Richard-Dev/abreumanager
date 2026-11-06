<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { dashboard, login } from '@/routes';
</script>

<template>
    <Head title="Cadastra Link">
        <meta
            name="description"
            content="Organize seus links importantes em um só lugar com o Cadastra Link."
        />
    </Head>

    <div
        class="min-h-screen overflow-hidden bg-[#f7f8f2] text-[#17231b] dark:bg-[#101710] dark:text-[#eff5eb]"
    >
        <header
            class="mx-auto flex max-w-7xl items-center justify-between px-6 py-6 lg:px-10"
        >
            <div class="flex items-center gap-3">
                <span
                    class="flex size-10 items-center justify-center rounded-xl bg-primary text-primary-foreground"
                    aria-hidden="true"
                >
                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                        class="size-5"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    >
                        <path
                            d="M10 13a5 5 0 0 0 7.07 0l3-3A5 5 0 0 0 13 2.93l-1.72 1.71"
                        />
                        <path
                            d="M14 11a5 5 0 0 0-7.07 0l-3 3A5 5 0 0 0 11 21.07l1.71-1.71"
                        />
                    </svg>
                </span>
                <span class="text-lg font-bold tracking-tight"
                    >Cadastra Link</span
                >
            </div>

            <Link
                :href="$page.props.auth.user ? dashboard() : login()"
                class="rounded-full px-5 py-2.5 text-sm font-semibold text-[#334238] transition hover:bg-black/5 dark:text-[#d4dfd0] dark:hover:bg-white/10"
            >
                {{ $page.props.auth.user ? 'Minha aplicação' : 'Entrar' }}
            </Link>
        </header>

        <main>
            <section
                class="mx-auto grid max-w-7xl items-center gap-14 px-6 pt-10 pb-20 lg:grid-cols-[1fr_1.05fr] lg:gap-16 lg:px-10 lg:pt-16 lg:pb-28"
            >
                <div class="relative z-10">
                    <div
                        class="mb-7 inline-flex items-center gap-2 rounded-full border border-[#dce4d5] bg-white/70 px-4 py-2 text-sm font-medium text-[#526352] dark:border-white/10 dark:bg-white/5 dark:text-[#c0d0bc]"
                    >
                        <span class="size-2 rounded-full bg-[#81b83b]" />
                        Seus links, finalmente organizados
                    </div>

                    <h1
                        class="max-w-2xl text-5xl leading-[1.08] font-semibold tracking-[-0.055em] sm:text-6xl lg:text-7xl"
                    >
                        Nunca mais perca
                        <span class="text-[#709f32] dark:text-[#c9f36a]"
                            >links importantes.</span
                        >
                    </h1>

                    <p
                        class="mt-7 max-w-xl text-lg leading-8 text-[#647166] dark:text-[#b2bdb0]"
                    >
                        Chega de procurar em blocos de notas, conversas no
                        WhatsApp ou em qualquer outro lugar. Guarde tudo em um
                        só lugar e encontre o que precisa em segundos.
                    </p>

                    <div
                        class="mt-9 flex flex-col gap-4 sm:flex-row sm:items-center"
                    >
                        <Link
                            :href="
                                $page.props.auth.user ? dashboard() : login()
                            "
                            class="inline-flex items-center justify-center gap-3 rounded-full bg-primary px-7 py-4 font-semibold text-primary-foreground shadow-lg shadow-[#9fc958]/20 transition hover:-translate-y-0.5 hover:bg-[#b9e85c] focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-[#709f32]"
                        >
                            Ir para aplicação
                            <svg
                                viewBox="0 0 24 24"
                                fill="none"
                                class="size-5"
                                stroke="currentColor"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                aria-hidden="true"
                            >
                                <path d="M5 12h14" />
                                <path d="m12 5 7 7-7 7" />
                            </svg>
                        </Link>
                        <span
                            class="text-sm text-[#738074] dark:text-[#a7b3a5]"
                        >
                            Simples de guardar. Fácil de encontrar.
                        </span>
                    </div>
                </div>

                <div class="relative mx-auto w-full max-w-xl">
                    <div
                        class="absolute -inset-8 rounded-[3rem] bg-[#dff2c0] blur-3xl dark:bg-[#45602c]/30"
                        aria-hidden="true"
                    />
                    <div
                        class="relative rotate-1 rounded-[2rem] border border-[#e2e8dd] bg-white p-3 shadow-[0_32px_100px_-40px_rgba(27,49,26,0.35)] sm:p-5 dark:border-white/10 dark:bg-[#19231a]"
                    >
                        <div
                            class="rounded-[1.4rem] bg-[#f7f9f4] p-5 sm:p-7 dark:bg-[#111a12]"
                        >
                            <div class="flex items-center justify-between">
                                <div>
                                    <p
                                        class="text-xs font-medium tracking-wide text-[#859184] uppercase"
                                    >
                                        Seu espaço
                                    </p>
                                    <h2
                                        class="mt-1 text-xl font-semibold tracking-tight"
                                    >
                                        Meus links
                                    </h2>
                                </div>
                                <span
                                    class="flex size-10 items-center justify-center rounded-full bg-[#e6f4d0] text-[#5a852b] dark:bg-[#344926] dark:text-[#c9f36a]"
                                    aria-hidden="true"
                                >
                                    <svg
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        class="size-5"
                                        stroke="currentColor"
                                        stroke-width="1.8"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                    >
                                        <path d="M12 20h9" />
                                        <path
                                            d="M16.5 3.5a2.12 2.12 0 0 1 3 3L8 18l-4 1 1-4Z"
                                        />
                                    </svg>
                                </span>
                            </div>

                            <div
                                class="mt-6 flex items-center gap-3 rounded-xl border border-[#e7ece3] bg-white px-4 py-3 text-sm text-[#99a398] dark:border-white/10 dark:bg-[#19231a]"
                            >
                                <svg
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    class="size-4"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    aria-hidden="true"
                                >
                                    <circle cx="11" cy="11" r="8" />
                                    <path d="m21 21-4.35-4.35" />
                                </svg>
                                Buscar entre seus links...
                            </div>

                            <div class="mt-5 flex items-center gap-2">
                                <span
                                    class="rounded-full bg-[#dff1c5] px-3 py-1.5 text-xs font-semibold text-[#4c702c] dark:bg-[#344926] dark:text-[#c9f36a]"
                                >
                                    Todos
                                </span>
                                <span
                                    class="rounded-full bg-white px-3 py-1.5 text-xs font-medium text-[#778276] dark:bg-[#19231a] dark:text-[#b2bdb0]"
                                >
                                    Trabalho
                                </span>
                                <span
                                    class="rounded-full bg-white px-3 py-1.5 text-xs font-medium text-[#778276] dark:bg-[#19231a] dark:text-[#b2bdb0]"
                                >
                                    Estudos
                                </span>
                            </div>

                            <div class="mt-5 space-y-3">
                                <div
                                    class="flex items-center gap-4 rounded-xl border border-[#e9ede6] bg-white p-4 dark:border-white/10 dark:bg-[#19231a]"
                                >
                                    <span
                                        class="flex size-10 shrink-0 items-center justify-center rounded-lg bg-[#f0e9ff] text-sm font-bold text-[#7352b5]"
                                    >
                                        G
                                    </span>
                                    <div class="min-w-0 flex-1">
                                        <p
                                            class="truncate text-sm font-semibold"
                                        >
                                            Guia de produtividade
                                        </p>
                                        <p
                                            class="mt-1 truncate text-xs text-[#8b9689]"
                                        >
                                            exemplo.com/artigo
                                        </p>
                                    </div>
                                    <span
                                        class="rounded-full bg-[#f3f5f1] px-2.5 py-1 text-[11px] font-medium text-[#788276] dark:bg-white/5 dark:text-[#b2bdb0]"
                                    >
                                        Trabalho
                                    </span>
                                </div>
                                <div
                                    class="flex items-center gap-4 rounded-xl border border-[#e9ede6] bg-white p-4 dark:border-white/10 dark:bg-[#19231a]"
                                >
                                    <span
                                        class="flex size-10 shrink-0 items-center justify-center rounded-lg bg-[#e2f5ee] text-sm font-bold text-[#34886a]"
                                    >
                                        F
                                    </span>
                                    <div class="min-w-0 flex-1">
                                        <p
                                            class="truncate text-sm font-semibold"
                                        >
                                            Referência de design
                                        </p>
                                        <p
                                            class="mt-1 truncate text-xs text-[#8b9689]"
                                        >
                                            exemplo.com/design
                                        </p>
                                    </div>
                                    <span
                                        class="rounded-full bg-[#f3f5f1] px-2.5 py-1 text-[11px] font-medium text-[#788276] dark:bg-white/5 dark:text-[#b2bdb0]"
                                    >
                                        Estudos
                                    </span>
                                </div>
                                <div
                                    class="flex items-center gap-4 rounded-xl border border-[#e9ede6] bg-white p-4 dark:border-white/10 dark:bg-[#19231a]"
                                >
                                    <span
                                        class="flex size-10 shrink-0 items-center justify-center rounded-lg bg-[#fff0df] text-sm font-bold text-[#c17a31]"
                                    >
                                        D
                                    </span>
                                    <div class="min-w-0 flex-1">
                                        <p
                                            class="truncate text-sm font-semibold"
                                        >
                                            Documentação do projeto
                                        </p>
                                        <p
                                            class="mt-1 truncate text-xs text-[#8b9689]"
                                        >
                                            exemplo.com/docs
                                        </p>
                                    </div>
                                    <span
                                        class="rounded-full bg-[#f3f5f1] px-2.5 py-1 text-[11px] font-medium text-[#788276] dark:bg-white/5 dark:text-[#b2bdb0]"
                                    >
                                        Trabalho
                                    </span>
                                </div>
                            </div>

                            <p class="mt-5 text-center text-xs text-[#929c90]">
                                Tudo organizado. Tudo à mão.
                            </p>
                        </div>
                    </div>
                    <div
                        class="absolute -right-3 -bottom-5 rounded-2xl border border-[#e2e8dd] bg-white px-4 py-3 shadow-lg sm:-right-6 dark:border-white/10 dark:bg-[#19231a]"
                    >
                        <p class="text-xs text-[#839080]">Seus links</p>
                        <p class="mt-0.5 text-lg font-semibold">
                            Em um só lugar
                            <span class="text-[#709f32] dark:text-[#c9f36a]"
                                >✓</span
                            >
                        </p>
                    </div>
                </div>
            </section>

            <section class="border-t border-[#e5e9e1] dark:border-white/10">
                <div
                    class="mx-auto grid max-w-7xl gap-8 px-6 py-12 sm:grid-cols-3 lg:px-10 lg:py-14"
                >
                    <div class="flex gap-4">
                        <span
                            class="flex size-10 shrink-0 items-center justify-center rounded-xl bg-white text-[#709f32] shadow-sm dark:bg-white/5 dark:text-[#c9f36a]"
                            aria-hidden="true"
                        >
                            <svg
                                viewBox="0 0 24 24"
                                fill="none"
                                class="size-5"
                                stroke="currentColor"
                                stroke-width="1.8"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            >
                                <path d="M4 5h16M4 12h16M4 19h16" />
                                <circle
                                    cx="8"
                                    cy="5"
                                    r="1"
                                    fill="currentColor"
                                />
                                <circle
                                    cx="15"
                                    cy="12"
                                    r="1"
                                    fill="currentColor"
                                />
                                <circle
                                    cx="10"
                                    cy="19"
                                    r="1"
                                    fill="currentColor"
                                />
                            </svg>
                        </span>
                        <div>
                            <h2 class="font-semibold">Organize do seu jeito</h2>
                            <p
                                class="mt-1 text-sm leading-6 text-[#758174] dark:text-[#a7b3a5]"
                            >
                                Separe seus links por categorias e mantenha tudo
                                no lugar.
                            </p>
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <span
                            class="flex size-10 shrink-0 items-center justify-center rounded-xl bg-white text-[#709f32] shadow-sm dark:bg-white/5 dark:text-[#c9f36a]"
                            aria-hidden="true"
                        >
                            <svg
                                viewBox="0 0 24 24"
                                fill="none"
                                class="size-5"
                                stroke="currentColor"
                                stroke-width="1.8"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            >
                                <circle cx="11" cy="11" r="7" />
                                <path d="m20 20-4-4" />
                            </svg>
                        </span>
                        <div>
                            <h2 class="font-semibold">Encontre rapidinho</h2>
                            <p
                                class="mt-1 text-sm leading-6 text-[#758174] dark:text-[#a7b3a5]"
                            >
                                Chega de rolar conversas antigas para achar
                                aquele link.
                            </p>
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <span
                            class="flex size-10 shrink-0 items-center justify-center rounded-xl bg-white text-[#709f32] shadow-sm dark:bg-white/5 dark:text-[#c9f36a]"
                            aria-hidden="true"
                        >
                            <svg
                                viewBox="0 0 24 24"
                                fill="none"
                                class="size-5"
                                stroke="currentColor"
                                stroke-width="1.8"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            >
                                <path d="M12 3v18M3 12h18" />
                            </svg>
                        </span>
                        <div>
                            <h2 class="font-semibold">Guarde em segundos</h2>
                            <p
                                class="mt-1 text-sm leading-6 text-[#758174] dark:text-[#a7b3a5]"
                            >
                                Salve o que importa e volte quando precisar.
                            </p>
                        </div>
                    </div>
                </div>
            </section>
        </main>

        <footer
            class="mx-auto flex max-w-7xl flex-col gap-2 px-6 py-6 text-sm text-[#849082] sm:flex-row sm:items-center sm:justify-between lg:px-10"
        >
            <span class="font-semibold text-[#526352] dark:text-[#c0d0bc]"
                >Cadastra Link</span
            >
            <span>Seus links importantes, sempre por perto.</span>
        </footer>
    </div>
</template>
