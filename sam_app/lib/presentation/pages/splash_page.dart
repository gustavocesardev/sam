import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'package:sam_app/domain/viewmodels/splash_viewmodel.dart';

class SplashPage extends StatefulWidget {
  const SplashPage({super.key});

  @override
  State<SplashPage> createState() => _SplashPageState();
}

class _SplashPageState extends State<SplashPage> {
  @override
  void initState() {
    super.initState();
    _init();
  }

  Future<void> _init() async {
    final vm = context.read<SplashViewModel>();
    final route = await vm.initializeApp();

    if (!mounted) return;
    Navigator.pushReplacementNamed(context, route);
  }

  @override
  Widget build(BuildContext context) {
    return const Scaffold(
      backgroundColor: Color(0xFF0C0F1D),
      body: Center(
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Center(
              child: Column(
                children: [
                  Text('SAM', style: TextStyle(fontSize: 80)),
                  Text('Sua rede social universitária :)'),
                ],
              ),
            ),
            SizedBox(height: 45),
            CircularProgressIndicator(color: Colors.white70),
          ],
        ),
      ),
    );
  }
}
