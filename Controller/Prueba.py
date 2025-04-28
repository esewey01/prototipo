import numpy as np
import pandas as pd
import matplotlib.pyplot as plt


#PROGRAMA PARA EXPLICAR LA TRANSFERENCIA DE CALOR EN UN SÓLIDO
#CON LA LEY DE FOURIER
#K 9 VALORES DISTINTOS
#q VARIA DE 1 EN 1 HASTA 100

class babosa:
    




# Constantes
A = 1.0           # Área en m²
dT_dx = 10        # Gradiente de temperatura en K/m

# Rangos de variables
qsolar_range = np.arange(1, 101, 1)      # de 1 a 100
k_range = np.arange(9, 91, 9)            # de 9 a 90

# Almacenar resultados
resultados = []

# Cálculo numérico
for k in k_range:
    q_conduccion = -k * A * dT_dx        # Ley de Fourier: q = -kAdT/dx
    for qsolar in qsolar_range:
        q_total = q_conduccion + qsolar
        resultados.append({
            'k': k,
            'qsolar': qsolar,
            'q_conduccion': q_conduccion,
            'q_total': q_total
        })

# Convertir resultados a DataFrame
df = pd.DataFrame(resultados)

# Guardar como reporte CSV
df.to_csv('reporte_transferencia_calor.csv', index=False)
print("Reporte CSV generado como 'reporte_transferencia_calor.csv'.")

# Gráfica
plt.figure(figsize=(10, 6))
for k in k_range:
    subset = df[df['k'] == k]
    plt.plot(subset['qsolar'], subset['q_total'], label=f'k={k} W/m·K')

plt.title('Variación de q_total con qsolar para distintos k')
plt.xlabel('qsolar (W)')
plt.ylabel('q_total (W)')
plt.legend()
plt.grid(True)
plt.tight_layout()
plt.savefig('grafica_qtotal.png')  # Guardar la gráfica como imagen
plt.show()