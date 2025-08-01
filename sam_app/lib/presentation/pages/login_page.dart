import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'package:sam_app/core/routing/app_routes.dart';
import 'package:sam_app/domain/viewmodels/login_viewmodel.dart';

class LoginPage extends StatefulWidget {
  const LoginPage({super.key});

  @override
  State<LoginPage> createState() => _LoginPageState();
}

class _LoginPageState extends State<LoginPage> {
  final _emailController = TextEditingController();
  final _senhaController = TextEditingController();

  Future<void> _login() async {
  final vm = context.read<LoginViewModel>();
    await vm.loginAndStore(
      _emailController.text,
      _senhaController.text,
    );

    if (!mounted) return;

    final error = vm.errorMessage;
    if (error == null) {
      Navigator.pushReplacementNamed(context, AppRoutes.home);
    }
  }

  @override
  Widget build(BuildContext context) {
    final vm = context.watch<LoginViewModel>();

    WidgetsBinding.instance.addPostFrameCallback((_) {
      if (vm.errorMessage != null) {
        ScaffoldMessenger.of(context).clearSnackBars();
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            content: Text(vm.errorMessage!, style: TextStyle(color: Colors.white,),textAlign: TextAlign.center,),
            backgroundColor: Colors.red[700],
          ),
        );
        vm.clearError();
      }
    });

    return Scaffold(
      backgroundColor: Theme.of(context).scaffoldBackgroundColor,
      body: Padding(
        padding: const EdgeInsets.symmetric(horizontal: 32),
        child: Center(
          child: SingleChildScrollView(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                const Center(
                  child: Column(
                    children: [
                      Text('SAM', style: TextStyle(fontSize: 80)),
                      Text('Social Academic Media'),
                    ],
                  ),
                ),
                const SizedBox(height: 62),
                const Center(
                  child: Text(
                    'Acesse sua conta',
                    style: TextStyle(fontSize: 32),
                  ),
                ),
                const SizedBox(height: 4),
                const Center(
                  child: Text('Faça login com o e-mail institucional'),
                ),
                const SizedBox(height: 76),
                TextFormField(
                  controller: _emailController,
                  decoration: InputDecoration(labelText: 'E-mail'),
                ),
                const SizedBox(height: 16),
                TextFormField(
                  controller: _senhaController,
                  obscureText: true,
                  decoration: InputDecoration(labelText: 'Senha'),
                ),
                const SizedBox(height: 30),
                SizedBox(
                  width: double.infinity,
                  child: ElevatedButton(
                    style: ElevatedButton.styleFrom(
                      padding: const EdgeInsets.symmetric(
                        vertical: 16,
                        horizontal: 8,
                      ),
                    ),
                    onPressed: vm.loading ? null : _login,
                    child: vm.loading
                        ? const SizedBox(
                            height: 16,
                            width: 16,
                            child: CircularProgressIndicator(
                              strokeWidth: 2,
                              color: Colors.white,
                            ),
                          )
                        : const Text('Login', style: TextStyle(fontSize: 16)),
                  ),
                ),
                const SizedBox(height: 20),
                Row(
                  mainAxisAlignment: MainAxisAlignment.center,
                  children: [
                    const Text(
                      'Não tem conta?',
                      style: TextStyle(color: Colors.white70),
                    ),
                    TextButton(
                      onPressed: () {
                        Navigator.pushNamed(context, AppRoutes.instituicoes);
                      },
                      style: TextButton.styleFrom(
                        padding: EdgeInsets.zero,
                        minimumSize: Size(125, 40),
                        tapTargetSize: MaterialTapTargetSize.shrinkWrap,
                      ),
                      child: const Text('Cadastre-se agora'),
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
