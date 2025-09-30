import 'package:flutter/material.dart';
import 'package:sam_app/core/routing/app_routes.dart';

class RegisterPage extends StatefulWidget {
  const RegisterPage({super.key});

  @override
  State<RegisterPage> createState() => _RegisterPageState();
}

class _RegisterPageState extends State<RegisterPage> {
  final _nameController = TextEditingController();
  final _emailController = TextEditingController();
  final _passwordController = TextEditingController();
  final _confirmPasswordController = TextEditingController();

  int? _selectedCurso;

  bool _loading = false;
  String? _errorMessage;

  int? _selectedAnoInicio;
  int? _selectedAnoFim;

  final List<int> anos = [2023, 2024, 2025, 2026];

  Future<void> _register() async {
    if (_passwordController.text != _confirmPasswordController.text) {
      setState(() => _errorMessage = "As senhas não conferem");
      return;
    }

    setState(() {
      _loading = true;
      _errorMessage = null;
    });

    await Future.delayed(const Duration(seconds: 2));

    setState(() => _loading = false);

    if (!mounted) return;

    Navigator.pushReplacementNamed(context, AppRoutes.home);
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Theme.of(context).scaffoldBackgroundColor,
      body: Padding(
        padding: const EdgeInsets.symmetric(horizontal: 32),
        child: Center(
          child: SingleChildScrollView(
            child: Column(
              children: [
                const Column(
                  children: [
                    Text('SAM', style: TextStyle(fontSize: 80)),
                    Text('Social Academic Media'),
                  ],
                ),
                const SizedBox(height: 62),
                const Text('Crie sua conta', style: TextStyle(fontSize: 32)),
                const SizedBox(height: 16),
                const Center(
                  child: Text('Fundação Educacional do Município de Assis'),
                ),
                const SizedBox(height: 45),
                // Curso
                DropdownButtonFormField<int>(
                  value: _selectedCurso,
                  items: const [
                    DropdownMenuItem(
                      value: 6,
                      child: Text("Análise e Desenvolvimento de Sistemas"),
                    ),
                    DropdownMenuItem(value: 7, child: Text("Direito")),
                  ],
                  onChanged: (val) => setState(() => _selectedCurso = val),
                  decoration: const InputDecoration(labelText: 'Curso'),
                ),

                const SizedBox(height: 16),

                // Nome
                TextFormField(
                  controller: _nameController,
                  decoration: const InputDecoration(labelText: 'Nome completo'),
                ),
                const SizedBox(height: 16),

                // Email
                TextFormField(
                  controller: _emailController,
                  decoration: const InputDecoration(
                    labelText: 'E-mail institucional',
                  ),
                ),
                const SizedBox(height: 16),

                // Ano de início
                DropdownButtonFormField<int>(
                  onChanged: (val) => setState(() => _selectedAnoInicio = val),
                  items: anos
                      .map(
                        (ano) => DropdownMenuItem(
                          value: ano,
                          child: Text(ano.toString()),
                        ),
                      )
                      .toList(),
                  decoration: const InputDecoration(
                    labelText: 'Ano início curso',
                  ),
                ),

                const SizedBox(height: 16),

                // Ano de fim
                DropdownButtonFormField<int>(
                  onChanged: (val) => setState(() => _selectedAnoInicio = val),
                  items: anos
                      .map(
                        (ano) => DropdownMenuItem(
                          value: ano,
                          child: Text(ano.toString()),
                        ),
                      )
                      .toList(),
                  decoration: const InputDecoration(labelText: 'Ano fim curso'),
                ),

                // Senha
                TextFormField(
                  controller: _passwordController,
                  obscureText: true,
                  decoration: const InputDecoration(labelText: 'Senha'),
                ),
                const SizedBox(height: 16),

                // Confirmação Senha
                TextFormField(
                  controller: _confirmPasswordController,
                  obscureText: true,
                  decoration: const InputDecoration(
                    labelText: 'Confirmar senha',
                  ),
                ),
                const SizedBox(height: 30),

                // Botão
                SizedBox(
                  width: double.infinity,
                  child: ElevatedButton(
                    onPressed: _loading ? null : _register,
                    style: ElevatedButton.styleFrom(
                      padding: const EdgeInsets.symmetric(
                        vertical: 16,
                        horizontal: 8,
                      ),
                    ),
                    child: _loading
                        ? const SizedBox(
                            height: 16,
                            width: 16,
                            child: CircularProgressIndicator(
                              strokeWidth: 2,
                              color: Colors.white,
                            ),
                          )
                        : const Text(
                            'Cadastrar',
                            style: TextStyle(fontSize: 16),
                          ),
                  ),
                ),
                const SizedBox(height: 20),

                if (_errorMessage != null)
                  Text(
                    _errorMessage!,
                    style: TextStyle(color: Colors.red[400]),
                  ),

                Row(
                  mainAxisAlignment: MainAxisAlignment.center,
                  children: [
                    const Text(
                      'Já tem conta?',
                      style: TextStyle(color: Colors.white70),
                    ),
                    TextButton(
                      onPressed: () {
                        Navigator.pushReplacementNamed(
                          context,
                          AppRoutes.login,
                        );
                      },
                      child: const Text('Faça login'),
                    ),
                  ],
                ),
              ],
            ),
          ),
        ),
      ),
    );
  }
}
