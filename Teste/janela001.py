from tkinter import *

janela = Tk()
janela.title("Teste")
#janela.geometry("550x350+200+200")
janela.resizable(False, False)
janela.state("zoomed")
janela['bg'] = "#ffe4c4"
texto01 = Label(janela, text="minhas informações")
texto01.grid(column=0, row=0)
texto01['bg'] = "#ffe4c4"
botao = Button(janela, text="clique aqui!")
botao.grid(column=3, row=2)
janela.mainloop()
